<?php


namespace Booni3\AmazonShipping;


use Carbon\Carbon;

class ShipmentCreator
{
    public $businessType = 'B2C';
    public $shippingPurpose = 'SALE';
    public $clientReferenceId = 'no-ref';
    public $shipFrom = [];
    public $shipTo = [];
    public $containers = [];
    public $expectedServiceOffering;

    public function setBusinessType($val)
    {
        $this->businessType = $val;
    }

    public function setShippingPurpose($val)
    {
        $this->shippingPurpose = $val;
    }

    public function setClientReferenceId($val)
    {
        $this->clientReferenceId = $val;
    }

    public function setShipFrom($array)
    {
        $this->shipFrom = [
            "name" => $array['name'],
            "addressLine1" => $array['addressLine1'],
            "addressLine2" => $array['addressLine2'] ?? null,
            "addressLine3" => $array['addressLine3'] ?? null,
            "postalCode" => $array['postalCode'],
            "city" => $array['city'],
            "countryCode" => $array['countryCode'],
            "email" => $array['email'] ?? null,
            "phoneNumber" => $array['phoneNumber'] ?? null
        ];
    }

    public function setShipTo($array)
    {
        $this->shipTo = [
            "name" => $array['name'],
            "addressLine1" => $array['addressLine1'],
            "addressLine2" => $array['addressLine2'] ?? null,
            "addressLine3" => $array['addressLine3'] ?? null,
            "postalCode" => $array['postalCode'],
            "city" => $array['city'],
            "countryCode" => $array['countryCode'],
            "email" => $array['email'] ?? null,
            "phoneNumber" => $array['phoneNumber'] ?? null
        ];
    }

    public function setExpectedServiceOffering(Carbon $from, Carbon $to)
    {
        $this->expectedServiceOffering = [
            'receiveWindow' => [
                'start' => $from->getPreciseTimestamp(3),
                'end' => $to->getPreciseTimestamp(3),
            ]
        ];
    }

    public function addContainer(Container $container)
    {
        array_push($this->containers, $container->toArray());
    }
}