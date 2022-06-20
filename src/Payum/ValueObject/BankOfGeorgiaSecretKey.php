<?php

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\ValueObject;

final class BankOfGeorgiaSecretKey
{
    public function __construct(
        private string $secretKey)
    {
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }
}
