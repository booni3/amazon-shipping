<?php


namespace Booni3\AmazonShipping\Tests;


use Booni3\AmazonShipping\Container;
use Booni3\AmazonShipping\ShipmentCreator;

trait ShipmentCreatorStub
{
    /**
     * The amazon test data will not create a shipment within the system.
     * You can use this for checking login and getting services but no more.
     *
     * @return ShipmentCreator
     */
    protected function getTestCreatorAmazonTestData()
    {
        $container = (new Container([
            'id' => 123,
            'reference' => 123,
            'height_cm' => 12,
            'length_cm' => 36,
            'weight_kg' => 4,
            'width_cm' => 15
        ]))
            ->addItem([
                "title" => "A Product",
                "quantity" => 2,
                "unitPriceValue" => 14.99,
                "weight_kg" => 2
            ])
            ->addItem([
                "title" => "A Product",
                "quantity" => 2,
                "unitPriceValue" => 14.99,
                "weight_kg" => 2
            ]);

        $c = new ShipmentCreator();
        $c->setShipFrom($this->testAddress);
        $c->setShipTo($this->testAddress);
        $c->addContainer($container);
        $c->setExpectedServiceOffering(
            now()->next('day')->startOfDay(),
            now()->next('day')->endOfDay()
        );

        return $c;
    }

    /**
     * A real address will flow through the system as it would do in production
     *
     * @return ShipmentCreator
     */
    protected function getTestCreatorRealAddress()
    {
        $container = (new Container([
            'id' => 'adam-test',
            'reference' => 'adam-test',
            'height_cm' => 12,
            'length_cm' => 36,
            'weight_kg' => 4,
            'width_cm' => 15
        ]))
            ->addItem([
                "title" => "A Product",
                "quantity" => 2,
                "unitPriceValue" => 14.99,
                "weight_kg" => 2
            ])
            ->addItem([
                "title" => "A Product",
                "quantity" => 2,
                "unitPriceValue" => 14.99,
                "weight_kg" => 2
            ]);

        $c = new ShipmentCreator();

        $c->setShipFrom([
            'name' => 'Adam Lambert',
            'addressLine1' => 'Unit 5 Headlands Trading Estate',
            'postalCode' => 'SN2 7JQ',
            'city' => 'Swindon',
            'countryCode' => 'GB',
            'email' => 'adam.lamb3rt@gmail.com',
            'phoneNumber' => '07890039882'
        ]);

        $c->setShipTo([
            'name' => 'Adam Lambert',
            'addressLine1' => '78 Avenue De Gien',
            'postalCode' => 'SN16 9GY',
            'city' => 'Malmesbury',
            'countryCode' => 'GB',
            'email' => 'adam.lamb3rt@gmail.com',
            'phoneNumber' => '07890039882'
        ]);

        $c->addContainer($container);

        $c->setExpectedServiceOffering(
            now()->next('day')->startOfDay(),
            now()->next('day')->endOfDay()
        );

        return $c;
    }
}