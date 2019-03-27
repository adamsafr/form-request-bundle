<?php

namespace Adamsafr\FormRequestBundle\Exception;

class JsonDecodeException extends \Exception
{
    public function __construct(
        string $message = 'Invalid json string given.',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
