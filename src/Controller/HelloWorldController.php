<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Controller;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Client\BankOfGeorgiaClient;
use Symfony\Component\HttpFoundation\JsonResponse;

final class HelloWorldController
{
    public function __construct(
        private BankOfGeorgiaClient $bankOfGeorgiaClient
    )
    {
    }

    public function __invoke(): JsonResponse
    {
        $auth = $this->bankOfGeorgiaClient->authenticate();

        return new JsonResponse($auth);
    }
}
