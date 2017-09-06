<?php namespace Kattatzu\ShipIt;

use Kattatzu\ShipIt\Exception\AttributeNotValidException;

class QuotationRequest
{

    private $validProperties = array(
        'commune_id',
        'courrier_for_client',
        'destiny',
        'height',
        'is_payable',
        'length',
        'weight',
        'width'
    );

    private $data = array(
        'commune_id' => null,
        'courrier_for_client' => '',
        'destiny' => 'Domicilio',
        'height' => 0,
        //'is_payable' => false,
        'length' => 0,
        'weight' => 0,
        'width' => 0
    );


    /**
     * QuotationRequest constructor.
     * @param array $data
     */
    public function __construct(array $data = null)
    {
        if (is_array($data)) {
            foreach ($data as $varName => $value) {
                if (!in_array($varName, $this->validProperties)) {
                    throw new AttributeNotValidException($varName);
                }

                $this->data[$varName] = $value;
            }
        }
    }


    /**
     * Retorna los datos de la solicitud en un array
     *
     * @return array
     */
    public function toArray()
    {
        return $this->data;
    }

    /**
     * Retorna los datos en el formato que pide ShipIt
     *
     * @return array
     */
    public function toShipItFormat()
    {
        $data = $this->data;
        $data['address_attributes']['commune_id'] = $data['commune_id'];
        unset($data['commune_id']);

        if(is_null($data['weight'])){
            $data['weight'] = 'null';
        }

        return array('package' => $data);
    }

    /**
     * Retorna el valor de un atributo de la solicitud
     *
     * @param $varName
     * @return mixed|null
     */
    public function __get($varName)
    {
        return isset($this->data[$varName]) ? $this->data[$varName] : null;
    }

    /**
     * Setea el valor de un atributo de la solicitud
     *
     * @param $varName
     * @param $value
     * @return void
     */
    public function __set($varName, $value)
    {

        if (!in_array($varName, $this->validProperties)) {
            throw new AttributeNotValidException($varName);
        }

        $this->data[$varName] = $value;
    }
}