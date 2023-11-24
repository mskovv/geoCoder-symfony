<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;

class ClientException extends Exception
{
    public function __construct($message = null, int $code = 0, ?Exception $previous = null)
    {
        parent::__construct((string)$message, $code, $previous);
    }
}
