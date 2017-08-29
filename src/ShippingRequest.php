<?php namespace Kattatzu\ShipIt;

use Kattatzu\ShipIt\Exception\AttributeNotValidException;

class ShippingRequest
{
    // Tamaños del envio
    const SIZE_SMALL = 'Pequeño (10x10x10cm)';
    const SIZE_MEDIUM = 'Mediano (30x30x30cm)';
    const SIZE_LARGE = 'Grande (50x50x50cm)';
    const SIZE_XLARGE = 'Muy Grande (>60x60x60cm)';
    // Empaque
    const PACKING_NONE = 'Sin empaque';
    const PACKING_PAPERBOARD = 'Caja de Cartón';
    const PACKING_PLASTIC = 'Film Plástico';
    const PACKING_BURBLE = 'Caja + Burbuja';
    const PACKING_KRAFT = 'Papel Kraft';
    // Tipo de envio
    const DELIVERY_NORMAL = 'Normal';
    const DELIVERY_SATURDAY = 'Sábado';
    const DELIVERY_SUNDAY = 'Domingo';
    // Destino del envio
    const DESTINATION_HOME = 'Domicilio';
    const DESTINATION_CHILEXPRESS = 'Chilexpress';
    const DESTINATION_STARKEN = 'Starken-Turbus';
    // Couriers
    const COURIER_NONE = '';
    const COURIER_CHILEXPRESS = 'Chilexpress';
    const COURIER_STARKEN = 'Starken';


    private $validProperties = array(
        'reference',
        'full_name',
        'email',
        'items_count',
        'cellphone',
        'is_payable',
        'packing',
        'shipping_type',
        'destiny',
        'courier_for_client',
        'approx_size',
        'address_commune_id',
        'address_street',
        'address_number',
        'address_complement'
    );

    /**
     * @var array atributos de la solicitud
     */
    private $data = [
        'reference' => null,
        'full_name' => null,
        'email' => null,
        'items_count' => 0,
        'cellphone' => null,
        'is_payable' => false,
        'packing' => null,
        'shipping_type' => null,
        'destiny' => 'Domicilio',
        'courier_for_client' => null,
        'approx_size' => null,
        'address_commune_id' => null,
        'address_street' => null,
        'address_number' => null,
        'address_complement' => null,
        'address_coords_latitude' => null,
        'address_coords_longitude' => null
    ];

    /**
     * Constructor
     *
     * @param array|null $data
     * @throws AttributeNotValidException
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
    public function toShipItFormat($environment = 'production')
    {
        $data = $this->data;

        if ($environment === ShipIt::ENV_DEVELOPMENT) {
            $data['reference'] = 'TEST-' . $data['reference'];
        }

        $data['address_attributes']['commune_id'] = $data['address_commune_id'];
        $data['address_attributes']['street'] = $data['address_street'];
        $data['address_attributes']['number'] = $data['address_number'];
        $data['address_attributes']['complement'] = $data['address_complement'];
        //$data['address_attributes']['coords']['latitude'] = $data['address_coords_latitude'];
        //$data['address_attributes']['coords']['longitude'] = $data['address_coords_longitude'];

        unset($data['commune_id']);
        unset($data['address_commune_id']);
        unset($data['address_street']);
        unset($data['address_number']);
        unset($data['address_complement']);
        unset($data['address_coords_latitude']);
        unset($data['address_coords_longitude']);

        return $data;
    }

    /**
     * Retorna un atributo de la solicitud
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
