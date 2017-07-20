<?php namespace Kattatzu\ShipIt;

/**
 * Class Region
 * @package Kattatzu\ShipIt
 */
class Region
{
    /**
     * @var array atributos de la región
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
            $this->data = [
                "id" => $response->id,
                "name" => $response->name,
                "number" => $response->number,
                "roman_numeral" => $response->roman_numeral
            ];
        }
    }

    /**
     * Retorna los datos de la región en un array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * Retorna un atributo de la región
     *
     * @param $varName
     * @return mixed|null
     */
    public function __get($varName)
    {
        return isset($this->data[$varName]) ? $this->data[$varName] : null;
    }
}
