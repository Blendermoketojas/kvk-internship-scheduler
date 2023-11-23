<?php

namespace App\Exceptions;

use Exception;

class ModelNotProvidedInServiceException extends Exception
{
    public function __construct($service, $code = 0, Exception $previous = null)
    {
        $message = "Provide model property to $service service class";
        parent::__construct($message, $code, $previous);
    }
}
