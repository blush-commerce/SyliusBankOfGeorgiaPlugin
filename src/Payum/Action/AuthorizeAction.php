<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\Action;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Client\BankOfGeorgiaClient;
use Gigamarr\SyliusBankOfGeorgiaPlugin\Formatter\OrderToAuthorizeActionPayloadFormatter;
use GuzzleHttp\Exception\BadResponseException;
use Sylius\Component\Core\Model\OrderInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\Authorize;
use Psr\Log\LoggerInterface;

final class AuthorizeAction implements ActionInterface
{
    public function __construct(
        private BankOfGeorgiaClient $client,
        private OrderToAuthorizeActionPayloadFormatter $orderToPayloadFormatter,
        private LoggerInterface $logger
    )
    {    
    }
    
    public function execute($request)
    {
        /** @var OrderInterface $order */
        $order = $request->getModel();
        
        $payload = $this->orderToPayloadFormatter->format($order);

        try {
            $createOrderResponse = $this->client->post('/checkout/orders', [
                'body' => json_encode($payload),
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);
    
            $payment = $order->getLastPayment();
            $responseContents = json_decode($createOrderResponse->getBody()->getContents(), true);
    
            if ($createOrderResponse->getStatusCode() === 200) {
                $payment->setDetails($responseContents);
                
                $message = 'Created an order request for order ' . $order->getId();
                $this->logger->debug($message);
            } else {
                // TODO: do something to notify the user and/or administrator
                $message = 'Bank of Georgia API endpoint for creating orders returned unexpected response code ' . $createOrderResponse->getStatusCode() . ' response contents: ' . $createOrderResponse->getBody()->getContents();
                $this->logger->critical($message);
            }
        } catch (BadResponseException $e) {
            // TODO: don't allow order to transition to completed state
            $this->logger->critical('API errored when creating an order request for order ' . $order->getId() . ' contents: ' . $e->getResponse()->getBody());
        }
    }

    public function supports($request): bool
    {
        return
            $request instanceof Authorize &&
            $request->getModel() instanceof OrderInterface
        ;
    }
}
