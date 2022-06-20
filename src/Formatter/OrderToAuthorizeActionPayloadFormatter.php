<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Formatter;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Bundle\PayumBundle\Model\GatewayConfigInterface;

final class OrderToAuthorizeActionPayloadFormatter
{
    public function format(OrderInterface $order): array
    {
        $orderItems = $this->formatOrderItems($order);

        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $order->getLastPayment()->getMethod();
        /** @var GatewayConfigInterface $gatewayConfig */
        $gatewayConfig = $paymentMethod->getGatewayConfig()->getConfig();

        $payload = [
            'intent' => $gatewayConfig['intent'],
            'items' => $orderItems,
            'locale' => 'ka', // TODO: set this based on current locale context
            'shop_order_id' => (string) $order->getId(),
            'redirect_url' => 'https://blush.ge', // TODO: use a service for this
            'show_shop_order_id_on_extract' => $gatewayConfig['show_shop_order_id_on_extract'],
            'capture_method' => $gatewayConfig['capture_method'],
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => $gatewayConfig['currency_code'],
                        'value' => number_format(abs($order->getTotal()) / 100, 2)
                    ]
                ]
            ]
        ];

        return $payload;
    }

    private function formatOrderItems(OrderInterface $order): array
    {
        $items = [];

        foreach ($order->getItems() as $orderItem) {
            $items[] = [
                'amount' => (string) number_format(abs($orderItem->getUnitPrice()) / 100, 2),
                'description' => $orderItem->getProduct()->getName(), // https://github.com/Sylius/Sylius/issues/13916
                'quantity' => $orderItem->getQuantity(),
                'product_id' => (string) $orderItem->getProduct()->getId()
            ];
        }

        return $items;
    }
}
