<?php

namespace Zadarma_API;

use Throwable;

class ApiException extends \Exception {

    protected $responseBody;

    public function __construct($message, $code = 0, Throwable $previous = null)
    {
        if(is_array($message)) {
            $this->responseBody = $message;
            $message = isset($message['error']) ? $message['error'] : '';
        }

        parent::__construct($message, $code, $previous);
    }

    public function getResponseBody() {
        return $this->responseBody;
    }
}