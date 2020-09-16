<?php

namespace Booni3\AmazonShipping\Exceptions;

use Exception;

class AmazonShippingConfigException extends Exception
{
    public static function accountNumberMissing(): self
    {
        return new static("Account number missing");
    }

    public static function secretMissing()
    {
        return new static("Secret missing");
    }

    public static function clientIdMissing()
    {
        return new static("Client ID missing");
    }
}