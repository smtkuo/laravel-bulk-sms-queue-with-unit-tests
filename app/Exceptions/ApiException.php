<?php

namespace App\Exceptions;

use Exception;

class ApiException extends Exception
{
    protected $message;
    protected $statusCode;

    public function __construct(string $message = "", int $statusCode = 400)
    {
        parent::__construct($message);
        $this->message = $message;
        $this->statusCode = $statusCode;
    }
}
