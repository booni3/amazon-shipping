<?php

namespace Booni3\AmazonShipping\Api;

use Booni3\AmazonShipping\Exceptions\AmazonShippingInvalidLabelFormat;
use Booni3\AmazonShipping\ShipmentCreator;

class Shipment extends ApiClient
{
    public function getServiceOfferings(ShipmentCreator $shipmentCreator)
    {
        return $this->post($this->accountNumber.'/shipments/service-offerings', [
            "consignor" => null,
            "businessType" => $shipmentCreator->businessType,
            "shippingPurpose" => $shipmentCreator->shippingPurpose,
            "clientReferenceId" => $shipmentCreator->clientReferenceId,
            "shipFrom" => $shipmentCreator->shipFrom,
            "shipTo" => $shipmentCreator->shipTo,
            "containers" => $shipmentCreator->containers
        ]);
    }

    public function create(ShipmentCreator $shipmentCreator)
    {
        return $this->post($this->accountNumber.'/shipments', [
            "consignor" => null,
            "businessType" => $shipmentCreator->businessType,
            "shippingPurpose" => $shipmentCreator->shippingPurpose,
            "clientReferenceId" => $shipmentCreator->clientReferenceId,
            "shipFrom" => $shipmentCreator->shipFrom,
            "shipTo" => $shipmentCreator->shipTo,
            "containers" => $shipmentCreator->containers,
            "expectedServiceOffering" => $shipmentCreator->expectedServiceOffering
        ]);
    }

    public function confirm($shipmentId, string $token)
    {
        return $this->put("shipments/$shipmentId/confirm", [
            "serviceOfferingToken" => $token,
        ]);
    }

    public function cancel($shipmentId, $reason = null)
    {
        return $this->post("shipments/$shipmentId/cancel", [
            "cancellationReason" => $reason ?? 'Shipment no longer required',
        ]);
    }

    public function getShipment($shipmentId)
    {
        return $this->get("shipments/$shipmentId");
    }

    public function getLabel($shipmentId, $trackingId, $labelFormat = 'zpl203')
    {
        if($labelFormat == 'zpl203'){
            $format = 'labelFormat=ZPL&labelResolution=203';
        }elseif($labelFormat == 'zpl300'){
            $format = 'labelFormat=ZPL&labelResolution=300';
        }elseif($labelFormat == 'png'){
            $format = '';
        } else {
            throw new AmazonShippingInvalidLabelFormat();
        }

        return $this->get("shipments/$shipmentId/containers/$trackingId/label?$format");
    }

    public function getContainerStatus($shipmentId)
    {
        return $this->get("shipments/$shipmentId/container-status");
    }
}