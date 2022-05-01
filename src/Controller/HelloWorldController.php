<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Controller;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Processor\AuthorizationProcessor;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

final class HelloWorldController
{
    public function __construct(
        private AuthorizationProcessor $authorizationProcessor,
        private OrderRepositoryInterface $orderRepository
    )
    {
    }

    public function __invoke(): JsonResponse
    {
        $order = $this->orderRepository->find(1133);
        $this->authorizationProcessor->process($order);

        return new JsonResponse(['message' => 'Logged it :-)']);
    }
}
