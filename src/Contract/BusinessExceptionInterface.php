<?php

namespace Three\LaravelResponse\Contract;

interface BusinessExceptionInterface
{
    public function getBusinessCode(): int;

    public function getData(): array;
}