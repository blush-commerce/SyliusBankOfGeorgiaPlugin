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
            orderBy: ['createdAd' => 'DESC']
        )[0];

        switch ($this->orderIsPreAuthorizedAndOrSuccessful($latestStatusChangeCallback)) {
            case true:
                return $payment->getState() === OrderPaymentStates::STATE_AUTHORIZED;
            case false:
                return true;
            case null:
                return false;
        }
    }

    private function orderIsPreAuthorizedAndOrSuccessful(?StatusChangeCallback $callback): ?bool
    {
        if ($this->_orderPaymentSuccessful($callback)) {
            switch ($callback->getPreAuthStatus()) {
                case 'in_progress': // in_progress means order is pre-authorized and ready to be either unblocked (refunded) or verified (given to merchant)
                    return true;
                case null:
                    return false;
            }
        }

        return null;
    }

    private function _orderPaymentSuccessful(?StatusChangeCallback $callback): bool
    {
        return !is_null($callback) && 'success' === $callback->getStatus();
    }
}
