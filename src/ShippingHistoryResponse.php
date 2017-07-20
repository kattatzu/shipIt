<?php namespace Kattatzu\ShipIt;

class ShippingHistoryResponse
{

    protected $deliveries = [];

    public function __construct($response)
    {
        if (is_array($response)) {
            foreach ($response as $delivery) {
                $this->add($delivery);
            }
        }

    }

    public function add($delivery)
    {
        $this->deliveries[] = new Shipping($delivery);
    }

    public function getDeliveries(){
        return $this->deliveries;
    }

    public function toArray(){
        $deliveries = [];

        foreach($this->deliveries as $delivery){
            $deliveries[] = $delivery->toArray();
        }

        return $deliveries;
    }
}