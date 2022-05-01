<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\Factory;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\ValueObject\BankOfGeorgiaClientId;
use Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\ValueObject\BankOfGeorgiaSecretKey;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

final class BankOfGeorgiaGatewayFactory extends GatewayFactory
{
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            'payum.factory_name' => 'bank_of_georgia',
            'payum.factory_title' => 'Bank of Georgia'
        ]);

        $config['payum.client_id'] = function (ArrayObject $config) {
            return new BankOfGeorgiaClientId($config['client_id']);
        };

        $config['payum.secret_key'] = function (ArrayObject $config) {
            return new BankOfGeorgiaSecretKey($config['secret_key']);
        };
    }
}
