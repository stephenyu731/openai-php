<?php

namespace SH\OpenAI;

class Exception extends \Exception
{
    protected $httpStatusCode;

    public function __construct($message = '', $httpStatusCode = 0, $code = 0, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->httpStatusCode = $httpStatusCode;
    }

    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }
}
