<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Controller;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Entity\StatusChangeCallbackInterface;
use Doctrine\Persistence\ObjectManager;
use Psr\Log\LoggerInterface;
use SM\Factory\FactoryInterface as StateMachineFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Order\OrderTransitions;
use Sylius\Component\Payment\PaymentTransitions;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class PaymentStatusChangeCallbackController
{
    public function __construct(
        private RepositoryInterface $orderRepository,
        private FactoryInterface $callbackFactory,
        private RepositoryInterface $callbackRepository,
        private StateMachineFactoryInterface $stateMachineFactory,
        private ObjectManager $paymentManager,
        private ObjectManager $orderManager,
        private LoggerInterface $logger
    )
    {
    }

    public function __invoke(Request $request): Response
    {
        /** @var Order $order */
        $order = $this->orderRepository->findOneBy(
            ['id' => $request->get('shop_order_id')]
        );

        // TODO: check that payment uses BOG gateway
        if ($order) {
            $payment = $order->getLastPayment();
            $paymentDetails = $payment->getDetails(); // TODO: this could be null.

            if (
                isset($paymentDetails['payment_hash']) &&
                $request->get('payment_hash') === $paymentDetails['payment_hash']
            ) {
                /** @var StatusChangeCallbackInterface $callback */
                $callback = $this->callbackFactory->createNew();

                $callback->setOrderId($request->get('order_id')); // BOG's internal order id, not related to sylius order
                $callback->setStatus($request->get('status'));
                $callback->setPaymentHash($request->get('payment_hash'));
                $callback->setIpayPaymentId($request->get('ipay_payment_id'));
                $callback->setStatusDescription($request->get('status_description'));
                $callback->setOrder($order);
                $callback->setPaymentMethod($request->get('payment_method'));
                $callback->setCardType($request->get('card_type'));
                $callback->setPan($request->get('pan'));
                $callback->setTransactionId($request->get('transaction_id'));
                $callback->setPreAuthStatus($request->get('pre_auth_status'));
                $callback->setCaptureMethod($request->get('capture_method'));

                $this->callbackRepository->add($callback);

                $paymentStateMachine = $this->stateMachineFactory->get($payment, PaymentTransitions::GRAPH);
                $orderStateMachine = $this->stateMachineFactory->get($order, OrderTransitions::GRAPH);

                switch ($callback->getStatus()) {
                    case StatusChangeCallbackInterface::STATUS_SUCCESS:
                        $this->processSuccessCallback($callback, $payment);
                        break;
                    case StatusChangeCallbackInterface::STATUS_ERROR:
                        $orderStateMachine->apply(OrderTransitions::TRANSITION_CANCEL);
                        $this->orderManager->flush();
                        break;
                    case StatusChangeCallbackInterface::STATUS_IN_PROGRESS:
                        $paymentStateMachine->apply(PaymentTransitions::TRANSITION_PROCESS);
                        $this->paymentManager->flush();
                        break;
                }

                $message = 'Successfully added a status change callback for order with ID ' . $order->getId() . '. Callback contained status: "' . $callback->getStatus() . '"';
                $this->logger->debug($message);

                return new Response(null, 200);
            }

            $message = 'Payment hash returned by Bank of Georgia did not match the one stored in database for order with ID: ' . $order->getId() . ' request content: ' . $request->getContent();
            $this->logger->critical($message);

            return new Response(null, 401);
        }

        $message = 'Bank of Georgia returned a callback for order with ID: ' . $request->get('shop_order_id') . ' - order with this ID does not exist. request content: ' . $request->getContent();
        $this->logger->critical($message);

        return new Response(null, 404);
    }

    private function processSuccessCallback(StatusChangeCallbackInterface $callback, PaymentInterface $payment): void
    {
        $paymentStateMachine = $this->stateMachineFactory->get($payment, PaymentTransitions::GRAPH);

        $transition = $callback->usesPreAuthorization() ? PaymentTransitions::TRANSITION_AUTHORIZE : PaymentTransitions::TRANSITION_COMPLETE;

        $paymentStateMachine->apply($transition);

        $this->paymentManager->flush();
    }
}
