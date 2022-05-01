<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Formatter;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Enum\Intent;
use Gigamarr\SyliusBankOfGeorgiaPlugin\Enum\CaptureMethod;
use Gigamarr\SyliusBankOfGeorgiaPlugin\Enum\CurrencyCode;
use Sylius\Component\Core\Model\OrderInterface;

final class OrderToAuthorizeActionPayloadFormatter
{
    private const INTENT = [
        'CAPTURE'   => 'CAPTURE',
        'AUTHORIZE' => 'AUTHORIZE',
    ];

    private const CAPTURE_METHOD = [
        'AUTOMATIC' => 'AUTOMATIC',
        'MANUAL'    => 'MANUAL',
    ];

    private const CURRENCY_CODE = [
        'GEL' => 'GEL',
        'USD' => 'USD',
        'EUR' => 'EUR',
        'GBP' => 'GBP',
    ];

    public function format(OrderInterface $order): array
    {
        $orderItems = $this->formatOrderItems($order);

        $payload = [
            'intent' => self::INTENT['CAPTURE'], // TODO: make this configurable from the backend
            'items' => $orderItems,
            'locale' => 'ka', // TODO: set this based on current locale context
            'shop_order_id' => (string) $order->getId(),
            'redirect_url' => 'https://blush.ge', // TODO: make this configurable from the backend
            'show_shop_order_id_on_extract' => TRUE, // TODO: make this configurable from the backend
            'capture_method' => self::CAPTURE_METHOD['AUTOMATIC'], // TODO: make this configurable from the backend
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => self::CURRENCY_CODE['GEL'], // TODO: make this configurable from the backend
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
