<?php namespace Kattatzu\ShipIt;

class DeliveryRequestResponse
{
    /**
     * @var array atributos de la respuesta
     */
    private $data = [];

    /**
     * Constructor
     *
     * @param null $response
     */
    public function __construct($response = null)
    {
        if ($response) {
            $this->data = (array)$response;
        }
    }

    /**
     * Retorna los datos de la respuesta en un array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * Retorna un atributo de la respuesta
     *
     * @param $varName
     * @return mixed|null
     */
    public function __get($varName)
    {
        return isset($this->data[$varName]) ? $this->data[$varName] : null;
    }
}