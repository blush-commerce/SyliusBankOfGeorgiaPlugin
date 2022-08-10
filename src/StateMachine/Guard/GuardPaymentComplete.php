<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\StateMachine\Guard;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Entity\StatusChangeCallback;
use Sylius\Bundle\PayumBundle\Model\GatewayConfigInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Core\OrderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class GuardPaymentComplete
{
    public function __construct(
        private string $gatewayFactoryName,
        private RepositoryInterface $statusChangeCallbackRepository
    )
    {
    }

    public function __invoke(PaymentInterface $payment)
    {
        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $payment->getMethod();

        /** @var GatewayConfigInterface $gatewayConfig */
        $gatewayConfig = $paymentMethod->getGatewayConfig();

        if ($gatewayConfig->getFactoryName() !== $this->gatewayFactoryName) {
            return true;
        }

        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        /** @var StatusChangeCallback[] $statusChangeCallbacks */
        $statusChangeCallbacks = $this->statusChangeCallbackRepository->findBy(
            ['order' => $order],
            orderBy: ['createdAt' => 'DESC']
        );

        if (isset($statusChangeCallbacks[0]) && $statusChangeCallbacks[0]->isSuccessful()) {
            $allowCompletion = $statusChangeCallbacks[0]->usesPreAuthorization() ? $payment->getState() === PaymentInterface::STATE_AUTHORIZED : true;

            return $allowCompletion;
        }

        return false;
    }
}
