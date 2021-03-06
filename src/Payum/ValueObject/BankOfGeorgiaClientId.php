<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum\ValueObject;

final class BankOfGeorgiaClientId
{
    public function __construct(
        private string $clientId
    )
    {
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }
}
