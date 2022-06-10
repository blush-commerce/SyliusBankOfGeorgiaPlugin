<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\Action;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Client\BankOfGeorgiaClient;
use GuzzleHttp\Exception\BadResponseException;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\Refund;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class RefundAction implements ActionInterface
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

        try {
            $refundResponse = $this->client->post('/checkout/refund', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'form_params' => [
                    'order_id' => $payment->getDetails()['order_id']
                ]
            ]);

            if ($refundResponse->getStatusCode() === 200) {
                $message = 'Successfully refunded payment with ID ' . $payment->getId();
                $this->logger->debug($message);
            } else {
                // TODO: do not allow refund transition and notify the administrator
                $message = 'Bank of Georgia API endpoint for refunding returned unexpected status code ' . $refundResponse->getStatusCode() . ' response content: ' . $refundResponse->getBody()->getContents();
                $this->logger->critical($message);
            }
        } catch (BadResponseException $e) {
            // TODO: do not allow refund transition and notify the administrator
            $message = 'Unexpected response when trying to refund payment with ID ' . $payment->getId() . '. response content: ' . $e->getResponse()->getStatusCode() . ' ' . $e->getResponse()->getBody()->getContents();
            $this->logger->critical($message);
        }
    }

    public function supports($request)
    {
        /** @var PaymentInterface $payment */
        $payment = $request->getModel();

        return
            $request instanceof Refund &&
            $payment instanceof PaymentInterface
        ;
    }
}
