<?php

namespace App\Service;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValidatorService
{
    public function __construct(
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function validate(string $content, string $validatorDTO): array
    {
        /** @var $validatorDTO $dto */
        $dto = $this->serializer->deserialize($content, $validatorDTO, 'json');

        $errors = $this->validator->validate($dto);

        return [$dto, $errors];
    }
}