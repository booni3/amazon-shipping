<?php


namespace Booni3\AmazonShipping\Api;

use Booni3\AmazonShipping\Exceptions\AmazonShippingResponseCouldNotBeParsed;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class ApiClient
{
    /** @var Client */
    private $client;

    /** @var string */
    private $bearer;

    /** @var string */
    private $server;

    /** @var int */
    protected $accountNumber;

    public function __construct(Client $client, string $server = null, string $bearer = null, int $accountNumber = null)
    {
        $this->client = $client;
        $this->server = $server;
        $this->bearer = $bearer;
        $this->accountNumber = $accountNumber;
    }

    public function get($url = null, array $body = []): array
    {
        return $this->parse(function () use ($url, $body) {
            return $this->client->get($this->server.$url, [
                'json' => $body,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'x-business-identifier' => 'AmazonShipping_UK',
                    'x-transaction-identifier' => 'uuid',
                    'Authorization' => 'Bearer '.$this->bearer ?? ''
                ]
            ]);
        });
    }

    public function post($url = null, array $body = []): array
    {
        return $this->parse(function () use ($url, $body) {
            return $this->client->post($this->server.$url, [
                'json' => $body,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'x-business-identifier' => 'AmazonShipping_UK',
                    'x-transaction-identifier' => 'uuid',
                    'Authorization' => 'Bearer '.$this->bearer ?? ''
                ]
            ]);
        });
    }

    public function put($url = null, array $body = []): array
    {
        return $this->parse(function () use ($url, $body) {
            return $this->client->put($this->server.$url, [
                'json' => $body,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                    'x-business-identifier' => 'AmazonShipping_UK',
                    'x-transaction-identifier' => 'uuid',
                    'Authorization' => 'Bearer '.$this->bearer ?? ''
                ]
            ]);
        });
    }

    private function parse(callable $callback)
    {
        $response = call_user_func($callback);

        $json = json_decode((string)$response->getBody(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new AmazonShippingResponseCouldNotBeParsed((string) $response->getBody());
        }

        return $json;
    }

}
