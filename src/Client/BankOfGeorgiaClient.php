<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Client;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Resolver\GatewayConfigResolver;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class BankOfGeorgiaClient
{
    public function __construct(
        private ClientInterface $client,
        private GatewayConfigResolver $gatewayConfigResolver,
        private string $baseUrl,
        private LoggerInterface $logger
    )
    {
    }

    public function authenticate(): array
    {
        $gatewayConfig = $this->gatewayConfigResolver->resolve()->getConfig();

        $response = $this->client->request(
            'POST',
            $this->baseUrl . '/oauth2/token',
            [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded'
                ],
                'auth' => [$gatewayConfig['client_id'], $gatewayConfig['secret_key']],
                'form_params' => [
                    'grant_type' => 'client_credentials'
                ]
            ]
        );

        if ($response->getStatusCode() === 200) {
            return (array) json_decode($response->getBody()->getContents(), true);
        } else {
            // TODO: throw an error, log and alert someone via monolog channel that something went wrong here
        }
    }

    public function post($uri, array $options): ResponseInterface
    {
        $accessToken = $this->authenticate()['access_token'];

        if (isset($options['headers'])) {
            $options['headers'] = array_merge([
                'Authorization' => "Bearer $accessToken"
            ], $options['headers']);
        }

        return $this->client->request('POST', $this->baseUrl . $uri, $options);
    }
}
