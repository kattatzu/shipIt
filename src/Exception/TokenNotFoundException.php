<?php namespace Kattatzu\ShipIt\Exception;

use Exception;

class TokenNotFoundException extends Exception
{
    function __construct()
    {
        parent::__construct("Token not found");
    }
}
