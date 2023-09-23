<?php

namespace App\Service\EnhancedValidator;

use App\Contracts\RequestFillerInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class Validated
{
    private RequestFillerInterface $dto;
    private ConstraintViolationListInterface $errors;

    public function __construct(RequestFillerInterface $dto, ConstraintViolationListInterface $errors)
    {
        $this->dto = $dto;
        $this->errors = $errors;
    }

    public function getDto(): RequestFillerInterface
    {
        return $this->dto;
    }

    public function getErrors(): ConstraintViolationListInterface
    {
        return $this->errors;
    }
}