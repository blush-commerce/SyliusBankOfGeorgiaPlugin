<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\Authorize;
use Sylius\Component\Core\Model\PaymentInterface as SyliusPaymentInterface;

final class AuthorizeAction implements ActionInterface
{
    public function __construct(
    )
    {
    }

    public function execute($request)
    {
    }

    public function supports($request): bool
    {
        return
            $request instanceof Authorize &&
            $request->getModel() instanceof SyliusPaymentInterface
        ;
    }
}
