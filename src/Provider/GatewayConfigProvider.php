<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Provider;

use Sylius\Bundle\PayumBundle\Model\GatewayConfigInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class GatewayConfigProvider implements GatewayConfigProviderInterface
{
    public function __construct(
        private RepositoryInterface $gatewayConfigRepository,
        private string $factoryName
    )
    {
    }

    public function get(): ?array
    {
        $gatewayConfig = $this->gatewayConfigRepository->findOneBy(['factoryName' => $this->factoryName]);

        if (
            $gatewayConfig &&
            isset($gatewayConfig->getConfig()['client_id']) &&
            isset($gatewayConfig->getConfig()['secret_key'])
        ) {
            return $gatewayConfig->getConfig();
        }

        // TODO: throw an error or log that gateway with given factory name has not been found
    }
}