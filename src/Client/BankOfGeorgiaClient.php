<?php

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Client;

use GuzzleHttp\ClientInterface;

class BankOfGeorgiaClient
{
    public function __construct(
        private ClientInterface $client,
        string $baseUrl
    )
    {
    }

    public function authorize(string $clientId, string $secretKey): void
    {
        
    }
}
