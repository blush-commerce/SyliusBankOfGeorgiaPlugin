<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Controller;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Resolver\GatewayConfigResolver;
use Gigamarr\SyliusBankOfGeorgiaPlugin\Client\BankOfGeorgiaClient;
use Symfony\Component\HttpFoundation\JsonResponse;

final class HelloWorldController
{
    public function __construct(
        private GatewayConfigResolver $gatewayConfigResolver,
        private BankOfGeorgiaClient $bankOfGeorgiaClient
    )
    {
    }

    public function __invoke(): JsonResponse
    {
        $gatewayConfig = $this->gatewayConfigResolver->resolve()->getConfig();
        $auth = $this->bankOfGeorgiaClient->authenticate($gatewayConfig['client_id'], $gatewayConfig['secret_key']);

        return new JsonResponse($auth);
    }
}
