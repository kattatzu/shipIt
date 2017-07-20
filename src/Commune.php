<?php namespace Kattatzu\ShipIt;

/**
 * Class Commune
 * @package Kattatzu\ShipIt
 */
class Commune
{
    /**
     * @var array atributos de la ciudad
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
                "region_id" => $response->region_id,
                "name" => $response->name,
                "code" => $response->code,
                "is_starken" => $response->is_starken,
                "is_chilexpress" => $response->is_chilexpress,
                "is_generic" => $response->is_generic,
                "is_reachable" => $response->is_reachable,
                "couriers_availables" => $response->couriers_availables,
                "is_available" => $response->is_available
            ];
        }
    }

    /**
     * Retorna los datos de la ciudad en un array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * Retorna un atributo de la ciudad
     *
     * @param $varName
     * @return mixed|null
     */
    public function __get($varName)
    {
        return isset($this->data[$varName]) ? $this->data[$varName] : null;
    }
}
