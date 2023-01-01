<?php

namespace App\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Attribute\HasNamedArguments;

#[Attribute]
class UniqueByEntityWithField extends Constraint
{
    public string $entityClass;
    public string $field;
    public string $message = '{{ entityClass }} with {{ field }} already exists.';

    #[HasNamedArguments]
    public function __construct(
        string $entityClass,
        string $field,
        mixed $options = null,
        array $groups = null,
        mixed $payload = null
    ) {
        parent::__construct($options, $groups, $payload);

        $this->field = $field;
        $this->entityClass = $entityClass;
    }
}