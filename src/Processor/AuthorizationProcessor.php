<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Processor;

use Sylius\Component\Core\Model\OrderInterface;
use Payum\Core\Payum;

final class AuthorizationProcessor implements ProcessorInterface
{
    public function __construct(private Payum $payum)
    {
    }

    public function process(OrderInterface $order): void
    {
        // TODO: execute authorization action
    }
}
