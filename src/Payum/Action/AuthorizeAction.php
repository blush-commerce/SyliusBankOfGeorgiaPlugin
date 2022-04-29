<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\Capture;
use GuzzleHttp\Client;
use Sylius\Component\Core\Model\PaymentInterface as SyliusPaymentInterface;

final class AuthorizeAction implements ActionInterface
{
    private Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function execute($request)
    {
    }

    public function supports($request): bool
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof SyliusPaymentInterface
        ;
    }
}
