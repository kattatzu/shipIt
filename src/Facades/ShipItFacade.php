<?php namespace Kattatzu\ShipIt\Facades;

use Illuminate\Support\Facades\Facade;
use Kattatzu\ShipIt\ShipIt;

class ShipItFacade extends Facade
{
    /**
     * @return mixed
     */
    protected static function getFacadeAccessor()
    {
        return ShipIt::class;
    }
}
