# ShipIt

Librería que permite la integración con el API de ShipIt (https://developers.shipit.cl/docs) para 
enviar solcitudes de delivery, consultar su estados y otras acciones.

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
var_dump($shipIt->getEconomicQuotation($request)->toArray());
```

```php
array:3 [▼
  0 => array:5 [▼
    "courier" => "correos"
    "delivery_time" => "1"
    "interval" => "1.5"
    "pv" => 1.0
    "total" => 2596
  ]
  1 => array:5 [▼
    "courier" => "chilexpress"
    "delivery_time" => "1"
    "interval" => "0..1.5"
    "pv" => 1.0
    "total" => 2960
  ]
  2 => array:5 [▼
    "courier" => "starken"
    "delivery_time" => "1"
    "interval" => "1"
    "pv" => 1.0
    "total" => 3271
  ]
]
```


