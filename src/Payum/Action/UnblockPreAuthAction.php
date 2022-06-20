<?php

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\Action;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Client\BankOfGeorgiaClient;
use Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\Request\UnblockPreAuth;
use GuzzleHttp\Exception\BadResponseException;
use Payum\Core\Action\ActionInterface;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\OrderPaymentStates;

final class UnblockPreAuthAction implements ActionInterface
{
    public function __construct(
        private BankOfGeorgiaClient $client,
        private LoggerInterface $logger
    )
    {
    }

    public function execute($request)
    {
        /** @var OrderInterface $order */
        $order = $request->getModel();
        $orderId = $order->getId();

        $payload = [
            'auth_type' => 'CANCEL'
        ];

        if (
            $order->getPaymentState() === OrderPaymentStates::STATE_AUTHORIZED &&
            $order->getLastPayment()->getState() === PaymentInterface::STATE_AUTHORIZED
        ) {
            try {
                $unblockPreAuthResponse = $this->client->post("/checkout/payment/$orderId/pre-auth/completion", [
                    'body' => json_encode($payload),
                    'headers' => [
                        'Content-Type' => 'application/json'
                    ]
                ]);

                if ($unblockPreAuthResponse->getStatusCode() === 200) {
                    $message = "Successfully unblocked pre-authorized funds for order $orderId";
                    $this->logger->debug($message);
                } else {
                    $message = 'Bank of Georgia API returned unexpected response code ' . $unblockPreAuthResponse->getStatusCode() . ' when unblocking pre-authorized funds of order ' . $orderId . ' contents: ' . $unblockPreAuthResponse->getBody()->getContents();
                    $this->logger->critical($message);
                }
            } catch (BadResponseException $e) {
                $message = 'Unexpected response when trying to unblock pre-authorized funds of order ' . $orderId . '. response contents: ' . $e->getResponse()->getStatusCode() . ' ' . $e->getResponse()->getBody()->getContents();
                $this->logger->critical($message);
            }
        }
    }

    public function supports($request)
    {
        return
            $request instanceof UnblockPreAuth &&
            $request->getModel() instanceof OrderInterface
        ;
    }
}
