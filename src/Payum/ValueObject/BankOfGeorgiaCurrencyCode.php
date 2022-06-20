<?php

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\ValueObject;

final class BankOfGeorgiaCurrencyCode
{
    public function __construct(
        private string $currencyCode
    )
    {
    }

    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }
}
