<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Processor;

use Payum\Core\Payum;
use Payum\Core\Request\Refund;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;

final class RefundProcessor
{
    public function __construct(
        private Payum $payum,
        private string $gatewayFactoryName
    )
    {
    }

    public function process(PaymentInterface $payment): void
    {
        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $payment->getMethod();

        $gatewayConfig = $paymentMethod->getGatewayConfig();

        if ($gatewayConfig->getFactoryName() !== $this->gatewayFactoryName) {
            // Do not process if it's not Bank of Georgia order
            return;
        }

        $this
            ->payum
            ->getGateway($gatewayConfig->getGatewayName())
            ->execute(new Refund($payment))
        ;
    }
}
