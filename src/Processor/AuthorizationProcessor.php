<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Processor;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Bundle\PayumBundle\Model\GatewayConfigInterface;
use Payum\Core\Payum;
use Payum\Core\Request\Authorize;

final class AuthorizationProcessor
{
    public function __construct(
        private Payum $payum,
        private string $gatewayFactoryName,
    )
    {
    }

    public function process(OrderInterface $order): void
    {
        /** @var PaymentInterface $payment */
        $payment = $order->getLastPayment();

        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $payment->getMethod();

        /** @var GatewayConfigInterface $gatewayConfig */
        $gatewayConfig = $paymentMethod->getGatewayConfig();

        if ($gatewayConfig->getFactoryName() !== $this->gatewayFactoryName) {
            // Do not process if it's not Bank of Georgia order
            return;
        }

        $this
            ->payum
            ->getGateway($gatewayConfig->getGatewayName())
            ->execute(new Authorize($order))
        ;
    }
}
