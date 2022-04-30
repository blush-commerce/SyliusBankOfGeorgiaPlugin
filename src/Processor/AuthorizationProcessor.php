<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Processor;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Client\BankOfGeorgiaClient;
use Gigamarr\SyliusBankOfGeorgiaPlugin\Resolver\GatewayConfigResolverInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Payum\Core\Payum;
use Psr\Log\LoggerInterface;

final class AuthorizationProcessor implements ProcessorInterface
{
    public function __construct(
        private Payum $payum,
        private GatewayConfigResolverInterface $gatewayConfigResolver,
        private BankOfGeorgiaClient $bankOfGeorgiaClient,
        private LoggerInterface $logger
    )
    {
    }

    public function process(OrderInterface $order): void
    {
        $gatewayConfig = $this->gatewayConfigResolver->resolve()->getConfig();
        $auth = $this->bankOfGeorgiaClient->authenticate($gatewayConfig['client_id'], $gatewayConfig['secret_key']);
    }
}
