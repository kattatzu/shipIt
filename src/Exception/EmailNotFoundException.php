<?php namespace Kattatzu\ShipIt\Exception;

use Exception;

class EmailNotFoundException extends Exception
{
    function __construct()
    {
        parent::__construct("Email not found");
    }
}
