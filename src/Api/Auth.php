<?php

namespace Booni3\AmazonShipping\Api;

class Auth extends ApiClient
{
    public function login($secret, $client_id, $env = 'sandbox')
    {
        return $this->post(null, [
            "grant_type" => "client_credentials",
            "client_secret" => $secret,
            "client_id" => $client_id,
            "scope" => $env == 'sandbox' ? 'ship::sandbox:api' : 'ship::production:api'
        ]);
    }
}