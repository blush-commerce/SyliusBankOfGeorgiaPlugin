<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\StateMachine\Guard;

use Sylius\Component\Core\Model\Payment;
use Sylius\Component\Core\OrderPaymentStates;

final class GuardPaymentComplete
{
    public function __invoke(Payment $payment)
    {
        return $payment->getState() === OrderPaymentStates::STATE_AUTHORIZED ? true : false;
    }
}
