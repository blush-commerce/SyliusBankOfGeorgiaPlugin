<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Resolver;

use Sylius\Component\Core\Model\OrderInterface;
use Payum\Core\Payum;

final class AuthorizeResolver
{
    public function __construct(private Payum $payum)
    {
    }

    public function resolve(OrderInterface $order): void {
        // TODO: execute authorize action and redirect user
    }
}
