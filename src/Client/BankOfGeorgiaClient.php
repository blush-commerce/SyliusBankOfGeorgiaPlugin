<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Client;

use GuzzleHttp\ClientInterface;

class BankOfGeorgiaClient
{
    public function __construct(
        private ClientInterface $client,
        private string $baseUrl
    )
    {
    }

    public function authenticate(string $clientId, string $secretKey): array
    {
        $response = $this->client->request(
            'POST',
            $this->baseUrl . '/oauth2/token',
            [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'auth' => [$clientId, $secretKey],
                'form_params' => [
                    'grant_type' => 'client_credentials'
                ]
            ]
        );

        // TODO:  check that everything went fine with the request

        return (array) json_decode($response->getBody()->getContents(), true);
    }
}
