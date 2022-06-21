<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\Factory;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\ValueObject\BankOfGeorgiaClientId;
use Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\ValueObject\BankOfGeorgiaSecretKey;
use Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\ValueObject\BankOfGeorgiaIntent;
use Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\ValueObject\BankOfGeorgiaCaptureMethod;
use Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\ValueObject\BankOfGeorgiaShowShopOrderIdOnExtract;
use Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\ValueObject\BankOfGeorgiaCurrencyCode;
use Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\ValueObject\BankOfGeorgiaRedirectUrl;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

final class BankOfGeorgiaGatewayFactory extends GatewayFactory
{
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            'payum.factory_name' => 'bank_of_georgia',
            'payum.factory_title' => 'Bank of Georgia',
        ]);

        $config['payum.client_id'] = function (ArrayObject $config) {
            return new BankOfGeorgiaClientId($config['client_id']);
        };

        $config['payum.secret_key'] = function (ArrayObject $config) {
            return new BankOfGeorgiaSecretKey($config['secret_key']);
        };

        $config['payum.intent'] = function (ArrayObject $config) {
            return new BankOfGeorgiaIntent($config['intent']);
        };

        $config['payum.capture_method'] = function (ArrayObject $config) {
            return new BankOfGeorgiaCaptureMethod($config['capture_method']);
        };

        $config['payum.shop_shop_order_id_on_extract'] = function (ArrayObject $config) {
            return new BankOfGeorgiaShowShopOrderIdOnExtract($config['show_shop_order_id_on_extract']);
        };

        $config['payum.currency_code'] = function (ArrayObject $config) {
            return new BankOfGeorgiaCurrencyCode($config['currency_code']);
        };

        $config['payum.redirect_url'] = function (ArrayObject $config) {
            return new BankOfGeorgiaRedirectUrl($config['redirect_url']);
        };
    }
}
