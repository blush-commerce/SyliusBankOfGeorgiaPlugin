<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum;

use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

final class BankOfGeorgiaPaymentGatewayFactory extends GatewayFactory
{
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            'payum.factory_name' => 'bank_of_georgia_payment',
            'payum.factory_title' => 'Bank Of Georgia Payment'
        ]);

        $config['payum.client'] = function (ArrayObject $config) {
            return new BankOfGeorgiaClient($config['client_id']);
        };
    }
}
