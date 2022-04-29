<?php

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\ValueObject;

final class BankOfGeorgiaSecretKey
{
    /** @var string */
    private $secretKey;

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }
}
