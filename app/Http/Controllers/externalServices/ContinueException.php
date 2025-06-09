<?php
namespace App\Http\Controllers\externalServices;
use Exception;

class ContinueException extends Exception
{
    public function __construct($message = "A continue exception occurred",$code = 200, Exception $previous = null)
    {
        // Ensure everything is assigned properly
        parent::__construct($message, $code, $previous);
    }
}
