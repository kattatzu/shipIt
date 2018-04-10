<?php namespace Kattatzu\ShipIt;

use Carbon\Carbon;
use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use Kattatzu\ShipIt\Events\ShipItCallbackPostEvent;
use Kattatzu\ShipIt\Events\ShipItCallbackPutEvent;
use Kattatzu\ShipIt\Exception\ConnectException;
use Kattatzu\ShipIt\Exception\EmailNotFoundException;
use Kattatzu\ShipIt\Exception\EndpointNotFoundException;
use Kattatzu\ShipIt\Exception\NumberNotValidException;
use Kattatzu\ShipIt\Exception\TokenNotFoundException;

/**
 * Class ShipIt
 * @package Kattatzu\ShipIt
 */
class ShipIt
{
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';

    const ENV_DEVELOPMENT = 'development';
    const ENV_PRODUCTION = 'production';

    protected $email = null;
    protected $token = null;
    protected $apiBase = 'http://api.shipit.cl/v/';

    protected $environment = 'production';

    private $providersTrakingUrls = [
        'chilexpress' => 'http://chilexpress.cl/Views/ChilexpressCL/Resultado-busqueda.aspx?DATA=:number',
        'starken' => 'http://www.starken.cl/seguimiento?codigo=:number',
        'correoschile' => 'http://www.correos.cl/SitePages/seguimiento/seguimiento.aspx?envio=:number'
    ];

    private $packageSizes = [
        29 => 'Pequeño (10x10x10cm)',
        49 => 'Mediano (30x30x30cm)',
        60 => 'Grande (50x50x50cm)',
        999999 => 'Muy Grande (>60x60x60cm)'
    ];

    /**
     * Constructor de la clase
     *
     * @param null $email email de la cuenta
     * @param null $token token de acceso
     */
    public function __construct($email = null, $token = null, $environment = 'production')
    {
        $this->email($email);
        $this->token($token);

        if (!in_array($environment, [ShipIt::ENV_DEVELOPMENT, ShipIt::ENV_PRODUCTION])) {
            $environment = ShipIt::ENV_PRODUCTION;
        }

        $this->environment($environment);

    }

    /**
     * Retorna el email registrado. Si se define un valor se sobre-escribe el valor actual.
     *
     * @param null $email email de la cuenta
     * @return string
     */
    function email($email = null)
    {
        if ($email !== null) {
            $this->email = $email;
        }

        return $this->email;
    }


    /**
     * Retorna el token de acceso. Si se define un valor se sobre-escribe el valor actual.
     *
     * @param null $token token de acceso
     * @return string
     */
    function token($token = null)
    {
        if ($token !== null) {
            $this->token = $token;
        }

        return $this->token;
    }

    /**
     * Retorna el environment. Si se define un valor se sobre-escribe el valor actual.
     *
     * @param null $environment ambiente
     * @return string
     */
    function environment($environment = null)
    {
        if ($environment !== null) {
            $this->environment = $environment;
        }

        return $this->environment;
    }

    /**
     * Retorna un array con las regiones disponibles en ShipIt
     *
     * @return array
     */
    public function getRegions($asArray = false)
    {
        $regionsResponse = $this->get(self::METHOD_GET, '/regions');
        $regions = [];

        if (is_array($regionsResponse)) {
            foreach ($regionsResponse as $regionData) {
                if ($asArray) {
                    $regions[] = (new Region($regionData))->toArray();
                } else {
                    $regions[] = new Region($regionData);
                }
            }
        }

        return $regions;
    }

    /**
     * Retorna la respuesta de un endpoint
     *
     * @param string $method método del endpoint
     * @param string $endpoint endpoint a consultar
     * @param array $data datos a enviar al endpoint
     * @throws TokenNotFoundException
     * @throws EmailNotFoundException
     * @throws ConnectException
     * @return object
     */
    public function get($method, $endpoint, array $data = null)
    {
        if ($this->token === null) {
            throw new TokenNotFoundException();
        }

        if ($this->email === null) {
            throw new EmailNotFoundException();
        }

        $endpoint = (strpos($endpoint, '/') == 0) ? substr($endpoint, 1) : $endpoint;
        $endpoint = $this->apiBase . $endpoint;

        try {
            $config = [
                'headers' => [
                    'Content-Type' => 'application/json',
                    'X-Shipit-Email' => $this->email,
                    'X-Shipit-Access-Token' => $this->token,
                    'Accept' => 'application/vnd.shipit.v2'
                ]
            ];

            if (in_array($method, array(self::METHOD_POST, self::METHOD_PUT))) {
                $config['json'] = $data;
            }


            $client = new \GuzzleHttp\Client();
            $res = $client->request($method, $endpoint, $config);

            if ($res->getStatusCode() == 404) {
                throw new EndpointNotFoundException($endpoint);
            }

            $response = json_decode($res->getBody());
        } catch (GuzzleConnectException $e) {
            throw new ConnectException($endpoint);
        }

        return $response;
    }

    /**
     * Retorna un array con las comunas disponibles en ShipIt
     *
     * @return array
     */
    public function getCommunes($asArray = false)
    {
        $communesResponse = $this->get(self::METHOD_GET, '/communes');
        $communes = [];

        if (is_array($communesResponse)) {
            foreach ($communesResponse as $communeData) {
                if ($asArray) {
                    $communes[] = (new Commune($communeData))->toArray();
                } else {
                    $communes[] = new Commune($communeData);
                }
            }
        }

        return $communes;
    }

    /**
     * Envia una solicitud de delivery
     *
     * @param ShippingRequest $request
     * @return ShippingRequestResponse
     */
    public function requestShipping(ShippingRequest $request)
    {
        $data = [
            'package' => $request->toShipItFormat($this->environment())
        ];

        $response = $this->get(self::METHOD_POST, '/packages', $data);

        return new ShippingRequestResponse($response);
    }

    /**
     * Envia un solicitud de delivery para multiples items
     *
     * @param array $items arreglo de ShippingRequest's
     * @return ShippingRequestResponse
     */
    public function requestMassiveShipping(array $items)
    {
        $data = [
            'packages' => []
        ];

        foreach ($items as $item) {
            $data['packages'][] = $item->toShipItFormat($this->environment());
        }

        $response = $this->get(self::METHOD_POST, '/packages/mass_create', $data);

        return new ShippingRequestResponse($response);
    }


    /**
     * Retorna el historial de despachos para una fecha
     *
     * @param null $date
     * @return ShippingHistoryResponse
     */
    public function getAllShippings($date = null)
    {
        if (is_null($date)) {
            $date = Carbon::today();
        }

        $date = (gettype($date) == 'object') ? Carbon::parse($date->format('Y-m-d')) : Carbon::parse($date);

        $params = [
            "year={$date->year}",
            "month={$date->month}",
            "day={$date->day}"
        ];

        $response = $this->get(self::METHOD_GET, '/packages?' . implode('&', $params));

        return new ShippingHistoryResponse($response);
    }


    /**
     * Retorna el detalle de un despacho
     *
     * @param $id
     * @return Shipping
     * @throws NumberNotValidException
     */
    public function getShipping($id)
    {
        if (!is_numeric($id)) {
            throw new NumberNotValidException($id);
        }

        $response = $this->get(self::METHOD_GET, '/packages/' . $id);

        return new Shipping($response, $this);
    }

    /**
     * Retorna el tamaño aproximado del paquete
     *
     * @param int $width ancho en cm.
     * @param int $height alto en cm.
     * @param int $length longitud en cm (profundidad)
     * @return string|null
     */
    public function getPackageSize($width, $height, $length)
    {
        if (!is_numeric($width) or !is_numeric($height) or !is_numeric($length)) {
            return null;
        }

        $packageSize = 0;
        $packageSize = ($packageSize < $height) ? $height : $packageSize;
        $packageSize = ($packageSize < $width) ? $width : $packageSize;
        $packageSize = ($packageSize < $length) ? $length : $packageSize;

        foreach ($this->packageSizes as $sizeLimit => $name) {
            if ($packageSize <= $sizeLimit) {
                return $name;
            }
        }

        return null;
    }

    /**
     * Recibe el callback entregado por ShipIt y lanza el evento que corresponda (solo Laravel)
     */
    public function receiveCallback()
    {
        $data = request()->all();
        $method = request()->method();

        if ($method == 'POST') {
            event(new ShipItCallbackPostEvent($data));
        }

        if (in_array($method, ['PUT', 'PATCH'])) {
            event(new ShipItCallbackPutEvent($data));
        }

        return response('ok');

    }

    /**
     * Retorna un listado de cotizaciones
     *
     * @param QuotationRequest $request
     * @return Quotation
     */
    public function getQuotation(QuotationRequest $request)
    {
        $data = $request->toShipItFormat();
        $response = $this->get(self::METHOD_POST, '/shippings/prices', $data);

        $quotation = new Quotation;
        if (isset($response->shipments)) {
            foreach ($response->shipments as $shipment) {
                $quotation->add($shipment);
            }
        }

        return $quotation;
    }

    /**
     * Retorna la cotización mas rápida y económica
     *
     * @param QuotationRequest $request
     * @return QuotationItem
     */
    public function getBestQuotation(QuotationRequest $request)
    {
        $data = $request->toShipItFormat();
        $response = $this->get(self::METHOD_POST, '/shippings/price', $data);

        return new QuotationItem($response->shipment);
    }

    /**
     * Retorna la cotización mas económica
     *
     * @param QuotationRequest $request
     * @return QuotationItem
     */
    public function getEconomicQuotation(QuotationRequest $request)
    {
        $data = $request->toShipItFormat();
        $response = $this->get(self::METHOD_POST, '/shippings/cost', $data);

        return new QuotationItem($response->shipment);
    }

    /**
     * Retorna la url de seguimiento de un envio
     *
     * @param string $provider proveedor de servicio
     * @param string $trackingNumber número de tracking
     * @return string
     */
    public function getTrackingUrl($provider, $trackingNumber)
    {
        if (!isset($this->providersTrakingUrls[$provider])) {
            return null;
        }

        $url = $this->providersTrakingUrls[$provider];

        return str_replace(":number", $trackingNumber, $url);
    }
}
