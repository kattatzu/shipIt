# ShipIt

Librería que permite la integración con el API de ShipIt (https://developers.shipit.cl/docs) para 
enviar solicitudes de despacho, consultar su estados y otras acciones.

## Obtener las Credenciales de Acceso

Para usar el API de ShipIt debes tener una cuenta y acceder al menú "API" para
copiar tu email y token de acceso.

https://clientes.shipit.cl/settings/api

## Instalación

Para instalar la librería ejecuta el siguiente comando en la consola:

```bash
composer require kattatzu/ship-it
```

## Uso de forma Standalone

Si tu sistema no trabaja con Laravel puedes usarlo de forma directa:

```php
use Kattatzu/ShipIt/ShipIt;

$shipIt = new ShipIt('EMAIL', 'TOKEN', 'development');
// o
$shipIt = new ShipIt;
$shipIt->email('EMAIL');
$shipIt->token('TOKEN');
$shipIt->environment(ShipIt::ENV_PRODUCTION);

var_export($shipIt->getCommunes());
```
```php
array:425 [▼
  0 => Commune {#208 ▼
    -data: array:10 [▼
      "id" => 1
      "region_id" => 1
      "name" => "ARICA"
      "code" => "15101"
      "is_starken" => null
      "is_chilexpress" => null
      "is_generic" => true
      "is_reachable" => true
      "couriers_availables" => {#211}
      "is_available" => false
    ]
  },
  ...
]
```

## Acciones Disponibles

### Obtener las Regiones y Comunas

Puedes listar las regiones y comunas que tiene registradas ShipIt para sincronizar 
tu sistema.

```php
$shipIt->getRegions();
$shipIt->getCommunes()
```
#### Ejemplo

```php
$regions = $shipIt->getRegions();
echo $regions[0]->name;
// "Arica y Parinacota"
```

### Obtener una Cotización

Puedes enviar los datos de tu despacho y obtener una cotización con las opciones
de cariers que dispone ShipIt.

Para esto es necesario que crees una instancia **QuotationRequest** para ser enviada al método **getQuotation**.

#### Ejemplo

```php
$request = new QuotationRequest([
    'commune_id' => 317,    // id de la Comuna en ShipIt
    'height' => 10,         // altura en centimetros
    'length' => 10,         // largo en centimetros
    'width' => 10,          // ancho en centimetros
    'weight' => 1          // peso en kilogramos
]);
var_dump($shipIt->getQuotation($request));
```

```php
Quotation {#210 ▼
  -items: array:3 [▼
    0 => QuotationItem {#208 ▼
      -data: array:5 [▼
        "courier" => "correos"
        "delivery_time" => "1"
        "interval" => "1.5"
        "pv" => 1.0
        "total" => 2596
      ]
    }
    1 => QuotationItem {#180 ▶}
    2 => QuotationItem {#186 ▶}
  ]
}
```

Puedes acceder a los items y sus propiedades de la siguiente forma:

```php
$request = new QuotationRequest(...);

$quotationItems = $shipIt->getQuotation($request)->getItems();

foreach($quotationItems as $item){
    echo $item->total . "<br>";
}

```

Si prefieres trabajarlos como array lo puedes hacer usando el método **toArray()**

```php
$request = new QuotationRequest(...);

$quotationItems = $shipIt->getQuotation($request)->toArray();

foreach($quotationItems as $item){
    echo $item['total'] . "<br>";
}
```



### Obtener la Cotización más Económica

Puedes enviar los datos de tu despacho y obtener la cotización más económica.


#### Ejemplo

```php
$request = new QuotationRequest([
    'commune_id' => 317,    // id de la Comuna en ShipIt
    'height' => 10,         // altura en centimetros
    'length' => 10,         // largo en centimetros
    'width' => 10,          // ancho en centimetros
    'weight' => 1          // peso en kilogramos
]);

$quotationItem = $shipIt->getEconomicQuotation($request);
echo $quotationItem->total;
```

### Obtener la Cotización más Conveniente

Puedes obtener la cotización más conveniente tanto en tiempo de respuesta (SLA) como en precio.


#### Ejemplo

```php
$request = new QuotationRequest([
    'commune_id' => 317,    // id de la Comuna en ShipIt
    'height' => 10,         // altura en centimetros
    'length' => 10,         // largo en centimetros
    'width' => 10,          // ancho en centimetros
    'weight' => 1          // peso en kilogramos
]);

$quotationItem = $shipIt->getBestQuotation($request);
echo $quotationItem->total;
```

### Enviar una solicitud de Despacho

Para enviar una solicitud de despacho debes crear una instancia **ShippingRequest** para ser enviada al método **requestShipping**:

```php
$request = new ShippingRequest([
    'reference' => 'S000001',
    'full_name' => 'José Eduardo Rios',
    'email' => 'cliente@gmail.com',
    'items_count' => 1,
    'cellphone' => '912341234',
    'is_payable' => false,
    'packing' => ShippingRequest::PACKING_NONE,
    'shipping_type' => ShippingRequest::DELIVERY_NORMAL,
    'destiny' => ShippingRequest::DESTINATION_HOME,
    'courier_for_client' => ShippingRequest::COURIER_CHILEXPRESS,
    'approx_size' => ShippingRequest::SIZE_SMALL,
    'address_commune_id' => 317,
    'address_street' => 'San Carlos',
    'address_number' => 123,
    'address_complement' => null,
]);

$response =  $shipIt->requestShipping($request);
echo $response->id; // id de la solicitud en ShipIt
```

Puedes trabajarlo como array usando el método **toArray()**:

```php
$request = new ShippingRequest(...);

$response =  $shipIt->requestShipping($request);
echo $response['id'];
```

### Listar Solicitudes de Despacho

Puedes consultar el historial de solicitudes realizadas por día usando el método **getAllShippings**:

```php
$history = $shipIt->getAllShippings('2017-04-06');

// ó

$history = $shipIt->getAllShippings(Carbon::yesterday());

// ó

$history = $shipIt->getAllShippings(); // Por defecto será la fecha actual

foreach($history->getShippings() as $shipping){
    echo $shipping->id . "<br>";
}
```

Puedes trabajar el resultado como array usando el método **toArray()**:

```php
$history = $shipIt->getAllShippings();

foreach($history->toArray() as $shipping){
    echo $shipping['id'] . "<br>";
}



