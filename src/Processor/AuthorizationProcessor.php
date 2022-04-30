<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Processor;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Client\BankOfGeorgiaClient;
use Sylius\Component\Core\Model\OrderInterface;
use Payum\Core\Payum;
use Psr\Log\LoggerInterface;

final class AuthorizationProcessor implements ProcessorInterface
{
    public function __construct(
        private Payum $payum,
        private BankOfGeorgiaClient $bankOfGeorgiaClient,
        private LoggerInterface $logger
    )
    {
    }

    public function process(OrderInterface $order): void
    {
        // TODO: execute authorization action
    }
}
