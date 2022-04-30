<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Resolver;

use Sylius\Bundle\PayumBundle\Model\GatewayConfigInterface;

interface GatewayConfigResolverInterface
{
    public function resolve(): GatewayConfigInterface;
}
