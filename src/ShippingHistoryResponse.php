<?php namespace Kattatzu\ShipIt;

class ShippingHistoryResponse
{

    protected $shippings = [];

    public function __construct($response)
    {
        if (is_array($response)) {
            foreach ($response as $shipping) {
                $this->add($shipping);
            }
        }

    }

    public function add($shipping)
    {
        $this->shippings[] = new Shipping($shipping);
    }

    public function getShippings()
    {
        return $this->shippings;
    }

    public function toArray()
    {
        $shippings = [];

        foreach ($this->shippings as $shipping) {
            $shippings[] = $shipping->toArray();
        }

        return $shippings;
    }
}