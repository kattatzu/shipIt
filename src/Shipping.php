<?php namespace Kattatzu\ShipIt;

class Shipping
{
    /**
     * @var array atributos del delivery
     */
    private $data = [];
    private $shipIt;

    /**
     * Constructor
     *
     * @param null $response
     * @param ShipIt $shipItIntance
     */
    public function __construct($response = null, $shipItIntance)
    {
        if ($response) {
            $this->data = (array)$response;
        }

        $this->shipIt = $shipItIntance;
    }


    /**
     * Retorna la url de seguimiento del envio
     *
     * @return string
     */
    public function getTrackingUrl()
    {
        if (is_null($this->courier_for_client) or is_null($this->tracking_number)) {
            return null;
        }

        return $this->shipIt->getTrackingUrl($this->courier_for_client, $this->tracking_number);
    }

    /**
     * Retorna los datos del delivery en un array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * Retorna un atributo del delivery
     *
     * @param $varName
     * @return mixed|null
     */
    public function __get($varName)
    {
        return isset($this->data[$varName]) ? $this->data[$varName] : null;
    }
}