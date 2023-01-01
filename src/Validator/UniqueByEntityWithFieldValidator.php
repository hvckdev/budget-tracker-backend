<?php

namespace App\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueByEntityWithFieldValidator extends ConstraintValidator
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @inheritDoc
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (! $constraint instanceof UniqueByEntityWithField) {
            throw new UnexpectedTypeException($constraint, UniqueByEntityWithField::class);
        }

        $entityRepository = $this->entityManager->getRepository($constraint->entityClass);

        $searchResult = $entityRepository->findBy([
            $constraint->field => $value,
        ]);

        if (count($searchResult) > 0) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}