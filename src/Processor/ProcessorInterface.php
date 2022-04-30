<?php

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Processor;

use Sylius\Component\Core\Model\OrderInterface;

interface ProcessorInterface
{
    public function process(OrderInterface $order): void;
}
