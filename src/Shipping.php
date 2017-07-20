<?php namespace Kattatzu\ShipIt;

class Shipping
{
    /**
     * @var array atributos del delivery
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