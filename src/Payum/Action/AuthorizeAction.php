<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\Action;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Client\BankOfGeorgiaClient;
use Gigamarr\SyliusBankOfGeorgiaPlugin\Formatter\OrderToAuthorizeActionPayloadFormatter;
use Sylius\Component\Core\Model\OrderInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\Authorize;
use Payum\Core\Reply\HttpResponse;
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

        $createOrderResponse = $this->client->post('/checkout/orders', [
            'body' => json_encode($payload),
            'headers' => [
                'Content-Type' => 'application/json'
            ]
        ]);

        if ($createOrderResponse->getStatusCode() === 200) {
            $this->logger->log('DEBUG', 'AuthorizeAction -> done.');
            // $responseContent = json_decode($createOrderResponse->getBody()->getContents(), true);

            // TODO: transition order to completed because throwing HttpResponse will not allow the order to transition its own state
            // TODO: transition payment state to processing
            throw new HttpResponse($createOrderResponse->getBody()->getContents(), 200);
        }

        // TODO: do something if request status code is not 200
    }

    public function supports($request): bool
    {
        return
            $request instanceof Authorize &&
            $request->getModel() instanceof OrderInterface
        ;
    }
}
