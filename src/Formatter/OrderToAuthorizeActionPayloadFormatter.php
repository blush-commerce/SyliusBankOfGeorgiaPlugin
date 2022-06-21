<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Formatter;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Bundle\PayumBundle\Model\GatewayConfigInterface;
use Gigamarr\SyliusBankOfGeorgiaPlugin\Exception\OrderAttributeIsNotResolvableException;

final class OrderToAuthorizeActionPayloadFormatter
{
    private const ORDER_RESOLVABLE_ATTRIBUTES = ['id', 'token value', 'number', 'state', 'locale code', 'items total'];

    public function format(OrderInterface $order): array
    {
        $orderItems = $this->formatOrderItems($order);

        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $order->getLastPayment()->getMethod();
        /** @var GatewayConfigInterface $gatewayConfig */
        $gatewayConfig = $paymentMethod->getGatewayConfig()->getConfig();

        // TODO: check payment's intent and capture method before before allowing refund, pre-auth complete and pre-auth unblock actions

        $payload = [
            'intent' => $gatewayConfig['intent'],
            'items' => $orderItems,
            'locale' => 'ka', // TODO: set this based on current locale context
            'shop_order_id' => (string) $order->getId(),
            'redirect_url' => $this->resolveRedirectUrl($order, $gatewayConfig['redirect_url']),
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

    private function resolveRedirectUrl(OrderInterface $order, string $redirectUrl): string
    {
        $orderAttributes = [];

        if (
            preg_match_all('/\{(\w+\s?\w+)\}/', $redirectUrl, $orderAttributes)
        ) {
            foreach ($orderAttributes[1] as $attribute) {
                if (in_array($attribute, self::ORDER_RESOLVABLE_ATTRIBUTES)) {
                    $attributeGetterMethod = 'get' . preg_replace('/\s/', '', ucwords($attribute));

                    $value = $order->{$attributeGetterMethod}();

                    $redirectUrl = preg_replace("/\{$attribute\}/", (string) $value, $redirectUrl);
                } else {
                    throw new OrderAttributeIsNotResolvableException("Error while resolving redirect url for Bank of Georgia order. Attribute '$attribute' can not be resolved.");
                }
            }
        }

        return $redirectUrl;
    }
}
