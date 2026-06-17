<?php

namespace Three\LaravelResponse\Exceptions;

class BusinessException extends \RuntimeException
{
    protected array $data;

    public function __construct(
        string        $message = '',
        protected int $businessCode = 1,
        array         $data = [],
        int           $code = 0,
        ?\Throwable   $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->data = $data;
    }

    public function getBusinessCode(): int
    {
        return $this->businessCode;
    }

    public function getData(): array
    {
        return $this->data;
    }
}