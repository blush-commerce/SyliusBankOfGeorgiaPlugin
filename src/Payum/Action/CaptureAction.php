<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\Action;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Client\BankOfGeorgiaClient;
use GuzzleHttp\Exception\BadResponseException;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\Capture;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class CaptureAction implements ActionInterface
{
    public function __construct(
        private BankOfGeorgiaClient $client,
        private LoggerInterface $logger
    )
    {
    }

    public function execute($request)
    {
        /** @var PaymentInterface $payment */
        $payment = $request->getModel();
        $orderId = $payment->getDetails()['order_id'];

        $payload = [
            'auth_type' => 'FULL_COMPLETE'
        ];

        try {
            $completePreAuthResponse = $this->client->post("/checkout/payment/$orderId/pre-auth/completion", [
                'body' => json_encode($payload),
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);

            if ($completePreAuthResponse->getStatusCode() === 200) {
                $message = 'Successfully completed pre-auth process for payment with ID ' . $payment->getId();
                $this->logger->debug($message);
            } else {
                // TODO: notify the administrator
                $message = 'Bank of Georgia API endpoint for completing pre-auth process returned unexpected status code ' . $completePreAuthResponse->getStatusCode() . ' content: ' . $completePreAuthResponse->getBody()->getContents();
                $this->logger->critical($message);
            }
        } catch (BadResponseException $e) {
            // TODO: don't allow state transition and notify the administrator
            $message = 'Unexpected response when trying to complete pre-auth process for payment with ID ' . $payment->getId() . '. response contents: ' . $e->getResponse()->getStatusCode() . ' ' . $e->getResponse()->getBody()->getContents();
            $this->logger->critical($message);
        }
    }

    public function supports($request)
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof PaymentInterface
        ;
    }
}
