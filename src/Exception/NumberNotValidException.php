<?php namespace Kattatzu\ShipIt\Exception;

use Exception;

class NumberNotValidException extends Exception
{
    function __construct($number)
    {
        parent::__construct("Number not valid ($number)");
    }
}
