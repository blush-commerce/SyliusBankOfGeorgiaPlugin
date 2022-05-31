<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sylius\Component\Core\Model\Order;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class PaymentStatusChangeCallbackController
{
    public function __construct(
        private RepositoryInterface $orderRepository
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
            $paymentDetails = $order->getLastPayment()->getDetails();

            if ($request->get('payment_hash') == $paymentDetails['payment_hash']) {
                return new Response(null, 200);
            }

            return new Response(null, 401);
        }

        return new Response(null, 404);
    }
}
