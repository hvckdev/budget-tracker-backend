<?php

namespace App\Service\EnhancedValidator;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValidatorService
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function validate(string $content, string $validatorDTO): Validated
    {
        /** @var $validatorDTO $dto */
        $dto = $this->serializer->deserialize($content, $validatorDTO, 'json');

        $errors = $this->validator->validate($dto);

        return new Validated($dto, $errors);
    }
}