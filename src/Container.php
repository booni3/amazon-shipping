<?php


namespace Booni3\AmazonShipping;


class Container
{
    public $data;

    public function __construct($array)
    {
        $this->data = [
            "containerType" => null,
            "identifiers" => [
                "containerReferenceId" => $array['id'],
                "clientReferenceId" => $array['reference']
            ],
            "items" => [],
            "dimensions" => [
                "height" => [
                    "unit" => "CM",
                    "value" => $array['height_cm']
                ],
                "length" => [
                    "unit" => "CM",
                    "value" => $array['length_cm']
                ],
                "weight" => [
                    "unit" => "kg",
                    "value" => $array['weight_kg']
                ],
                "width" => [
                    "unit" => "CM",
                    "value" => $array['width_cm']
                ]
            ],
            "value" => [
                "unit" => $array['value_unit'] ?? 'GBP',
                "value" => $array['value'] ?? 1
            ]
        ];
    }

    public function addItem($array): self
    {
        array_push($this->data['items'], [
            "title" => $array['title'] ?? 'Item',
            "quantity" => $array['quantity'] ?? 1,
            "unitPrice" => [
                "unit" => $array['unitPriceUnit'] ?? 'GBP',
                "value" => $array['unitPriceValue'] ?? 1
            ],
            "unitWeight" => [
                "unit" => "kg",
                "value" => $array['weight_kg']
            ]
        ]);

        return $this;
    }

    public function toArray()
    {
        return $this->data;
    }
}