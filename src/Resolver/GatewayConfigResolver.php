<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Resolver;

use Sylius\Bundle\PayumBundle\Model\GatewayConfigInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class GatewayConfigResolver implements GatewayConfigResolverInterface
{
    public function __construct(
        private RepositoryInterface $gatewayConfigRepository,
        private string $factoryName
    )
    {
    }

    public function resolve(): GatewayConfigInterface
    {
        $gatewayConfig = $this->gatewayConfigRepository->findOneBy(['factoryName' => $this->factoryName]);

        if ($gatewayConfig) {
            return $gatewayConfig;
        }

        // TODO: throw an error or log that gateway with given factory name has not been found
    }
}