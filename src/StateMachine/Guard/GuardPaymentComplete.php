<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\StateMachine\Guard;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Entity\StatusChangeCallback;
use Sylius\Bundle\PayumBundle\Model\GatewayConfigInterface;
use Sylius\Component\Core\Model\Payment;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Core\OrderPaymentStates;
use Sylius\Component\Core\Order;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class GuardPaymentComplete
{
    public function __construct(
        private string $gatewayFactoryName,
        private RepositoryInterface $statusChangeCallbackRepository
    )
    {
    }

    public function __invoke(Payment $payment)
    {
        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $payment->getMethod();

        /** @var GatewayConfigInterface $gatewayConfig */
        $gatewayConfig = $paymentMethod->getGatewayConfig();

        /**
         * TODO: this guard should not affect payments which are not using Bank of Georgia gateway
         */
        if ($gatewayConfig->getFactoryName() !== $this->gatewayFactoryName) {
            return;
        }

        /** @var Order $order */
        $order = $payment->getOrder();

        /** @var StatusChangeCallback|null $latestStatusChangeCallback */
        $latestStatusChangeCallback = $this->statusChangeCallbackRepository->findBy(
            ['order' => $order],
            orderBy: ['createdAt' => 'DESC']
        )[0];

        if ($latestStatusChangeCallback->isSuccessful()) {
            $allowCompletion = $latestStatusChangeCallback->usesPreAuthorization() ? $payment->getState() === OrderPaymentStates::STATE_AUTHORIZED : true;

            return $allowCompletion;
        }

        return false;
    }
}
