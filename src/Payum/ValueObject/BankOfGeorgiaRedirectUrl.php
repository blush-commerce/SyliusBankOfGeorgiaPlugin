<?php

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\ValueObject;

final class BankOfGeorgiaRedirectUrl
{
    public function __construct(
        private string $redirectUrl
    )
    {
    }

    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
    }
}
