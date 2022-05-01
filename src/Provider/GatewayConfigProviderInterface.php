<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Provider;

use Sylius\Bundle\PayumBundle\Model\GatewayConfigInterface;

interface GatewayConfigProviderInterface
{
    public function get(): ?array;
}
