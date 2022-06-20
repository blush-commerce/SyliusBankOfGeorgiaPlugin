<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\ValueObject;

final class BankOfGeorgiaShowShopOrderIdOnExtract
{
    public function __construct(
        private bool $showShopOrderIdOnExtract
    )
    {
    }

    public function getShowShopOrderIdOnExtract(): bool
    {
        return $this->showShopOrderIdOnExtract;
    }
}
