<?php

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Processor;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\Request\UnblockPreAuth;
use Payum\Core\Payum;
use Sylius\Bundle\PayumBundle\Model\GatewayConfigInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class PreAuthUnblockProcessor
{
    public function __construct(
        private Payum $payum,
        private string $gatewayFactoryName
    )
    {
    }

    public function process(OrderInterface $order): void
    {
        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $order->getLastPayment()->getMethod();

        /** @var GatewayConfigInterface $gatewayConfig */
        $gatewayConfig = $paymentMethod->getGatewayConfig();

        if ($gatewayConfig->getFactoryName() !== $this->gatewayFactoryName) {
            // Do not process if it's not Bank of Georgia order
            return;
        }

        $this
            ->payum
            ->getGateway($gatewayConfig->getGatewayName())
            ->execute(new UnblockPreAuth($order))
        ;
    }
}
