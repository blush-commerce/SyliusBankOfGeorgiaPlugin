<?php

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\ValueObject;

final class BankOfGeorgiaIntent
{
    public function __construct(
        private string $intent
    )
    {
    }

    public function getIntent(): string
    {
        return $this->intent;
    }
}
