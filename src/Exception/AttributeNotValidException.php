<?php namespace Kattatzu\ShipIt\Exception;

use Exception;

class AttributeNotValidException extends Exception
{
    function __construct($attribute)
    {
        parent::__construct("Attribute not valid ($attribute)");
    }
}
