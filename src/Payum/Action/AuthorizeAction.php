<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\Action;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Client\BankOfGeorgiaClient;
use Gigamarr\SyliusBankOfGeorgiaPlugin\Formatter\OrderToAuthorizeActionPayloadFormatter;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Request\Authorize;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\OrderInterface;

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
            $this->logger->log('DEBUG', $createOrderResponse->getBody()->getContents());
            $responseContent = json_decode($createOrderResponse->getBody()->getContents(), true);
            throw new HttpRedirect($responseContent['links'][1]['href']);
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
