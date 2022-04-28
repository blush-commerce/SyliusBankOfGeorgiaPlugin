<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Payum;

final class BankOfGeorgiaClient
{
    /** @var string */
    private $clientId;

    public function __construct(string $clientId)
    {
        $this->clientId = $clientId;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }
}
