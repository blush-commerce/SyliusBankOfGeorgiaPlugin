<?php

declare(strict_types=1);

namespace Gigamarr\SyliusBankOfGeorgiaPlugin\Client;

use Gigamarr\SyliusBankOfGeorgiaPlugin\Provider\GatewayConfigProvider;
use Gigamarr\SyliusBankOfGeorgiaPlugin\Exception\ApiReturnedUnexpectedStatusCodeException;
use Gigamarr\SyliusBankOfGeorgiaPlugin\Exception\ApiRequestFailedException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class BankOfGeorgiaClient
{
    public function __construct(
        private ClientInterface $client,
        private GatewayConfigProvider $gatewayConfigProvider,
        private string $baseUrl,
        private LoggerInterface $logger
    )
    {
    }

    private function authenticate(): array
    {
        $gatewayConfig = $this->gatewayConfigProvider->get();

        try {
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
                $message = 'Bank of Georgia API returned unexpected status code ' . $response->getStatusCode() . ' contents: ' . $response->getBody();
                $this->logger->critical($message);

                throw new ApiReturnedUnexpectedStatusCodeException($message);
            }
        } catch (BadResponseException $e) {
            $message = 'Bank of Georgia API oauth2 request failed. contents: ' . $e->getResponse()->getBody();
            $this->logger->critical($message);

            throw new ApiRequestFailedException($message);
        }

    }

    public function post($uri, array $options): ResponseInterface
    {
        $accessToken = $this->authenticate()['access_token'];

        $options['headers']['Authorization'] = "Bearer $accessToken";

        return $this->client->request('POST', $this->baseUrl . $uri, $options);
    }
}
