<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\Authorize;
use Psr\Log\LoggerInterface;
use Sylius\Component\Core\Model\OrderInterface;

final class AuthorizeAction implements ActionInterface
{
    public function __construct(
        private LoggerInterface $logger
    )
    {    
    }
    
    public function execute($request)
    {
        /** @var OrderInterface */
        $order = $request->getModel();
    }

    public function supports($request): bool
    {
        return
            $request instanceof Authorize &&
            $request->getModel() instanceof OrderInterface
        ;
    }
}
