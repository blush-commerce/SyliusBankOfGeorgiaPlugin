<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Processor;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Client\BankOfGeorgiaClient;
use Sylius\Component\Core\Model\OrderInterface;
use Payum\Core\Payum;

final class AuthorizationProcessor implements ProcessorInterface
{
    public function __construct(
        private Payum $payum,
        private BankOfGeorgiaClient $bankOfGeorgiaClient,
    )
    {
    }

    public function process(OrderInterface $order): void
    {
        $auth = $this->bankOfGeorgiaClient->authenticate();
    }
}
