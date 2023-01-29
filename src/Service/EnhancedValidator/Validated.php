<?php

namespace App\Service\EnhancedValidator;

use App\Contracts\RequestFillerInterface;

class Validated
{
    private RequestFillerInterface $dto;
    private array $errors;

    public function __construct(RequestFillerInterface $dto, array $errors)
    {
        $this->dto = $dto;
        $this->errors = $errors;
    }

    public function getDto(): RequestFillerInterface
    {
        return $this->dto;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}