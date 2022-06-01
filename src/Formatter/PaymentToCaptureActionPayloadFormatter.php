<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Formatter;

use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\OrderPaymentStates;

final class PaymentToCaptureActionPayloadFormatter
{
    private const AUTH_TYPE = [
        'FULL_COMPLETE' => 'FULL_COMPLETE',
        'PARTIAL_COMPLETE' => 'PARTIAL_COMPLETE',
        'CANCEL' => 'CANCEL'
    ];

    public function format(PaymentInterface $payment): array
    {
        $order = $payment->getOrder();

        $payload = [
            'auth_type' => ''
        ];

        if (
            $order->getState() === OrderInterface::STATE_CANCELLED &&
            $payment->getState() === PaymentInterface::STATE_AUTHORIZED
        ) {
            // if the order was cancelled and user has already authorized payment, then cancel that pre-auth to unblock funds of the user
            $payload['auth_type'] = self::AUTH_TYPE['CANCEL'];
        } else if (
            $order->getPaymentState() === OrderPaymentStates::STATE_AUTHORIZED &&
            $payment->getState() === PaymentInterface::STATE_AUTHORIZED
        ) {
            $payload['auth_type'] = self::AUTH_TYPE['FULL_COMPLETE'];
        }

        return $payload;
    }
}
