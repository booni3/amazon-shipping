<?php

namespace Booni3\AmazonShipping;

use Booni3\AmazonShipping\Api\Auth;
use Booni3\AmazonShipping\Api\Shipment;
use Booni3\AmazonShipping\Exceptions\AmazonShippingAuthenticationException;
use Booni3\AmazonShipping\Exceptions\AmazonShippingConfigException;
use GuzzleHttp\Client;

class AmazonShipping
{
    /** @var string */
    const BASE_URI = 'https://ship.amazon.co.uk/api/v2';

    /** @var string */
    const AUTH_URI = 'https://api.amazon.com/auth/O2/token';

    /** @var Client */
    protected $client;

    /** @var array */
    protected $config;

    /** @var string */
    protected $bearer;

    /** @var int */
    protected $accountNumber;

    public function __construct(array $config, Client $client = null)
    {
        $this->config = $config;

        $this->validateConfig();

        $this->client = $client ?: $this->makeClient();

        $this->accountNumber = $this->config['accountNumber'];

        if (! $this->bearer) {
            $this->refreshToken();
        }
    }

    public static function make(array $config, Client $client = null): self
    {
        return new static ($config, $client);
    }

    public function shipment(): Shipment
    {
        return new Shipment($this->client, $this->uri(), $this->bearer, $this->accountNumber);
    }

    protected function validateConfig()
    {
        if(! $this->config['accountNumber'] ?? null){
            throw AmazonShippingConfigException::accountNumberMissing();
        }

        if(! $this->config['client_id'] ?? null){
            throw AmazonShippingConfigException::clientIdMissing();
        }

        if(! $this->config['secret'] ?? null){
            throw AmazonShippingConfigException::secretMissing();
        }
    }

    protected function makeClient(): Client
    {
        return new Client([
            'timeout' => $this->config['timeout'] ?? 15
        ]);
    }

    protected function uri()
    {
        if ($this->sandbox()) {
            return self::BASE_URI.'/sandbox/';
        }

        return self::BASE_URI.'/';
    }

    protected function sandbox(): bool
    {
        return ($this->config['environment'] ?? null) === 'sandbox';
    }

    protected function refreshToken(): void
    {
        $response = (new Auth($this->client, self::AUTH_URI,null))->login(
            $this->config['secret'],
            $this->config['client_id'],
            $this->sandbox() ? 'sandbox' : 'production'
        );

        if(! $this->bearer = $response['access_token'] ?? null){
            throw new AmazonShippingAuthenticationException();
        }
    }
}
