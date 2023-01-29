<?php

namespace App\Request;

use DateTimeImmutable;
use App\Entity\Purchase;
use App\Contracts\RequestFillerInterface;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class PurchaseRequest implements RequestFillerInterface
{
    #[NotBlank]
    public string $name;

    #[Date]
    public string $created_at;

    #[NotBlank]
    #[All([
        new Collection([
            'name' => new NotBlank(),
            'category' => new NotBlank(), // TODO: check does category exist
            'price' => new NotBlank(),
        ]),
    ])]
    public array $products;

    /**
     * @param Purchase $entity
     *
     * @return void
     */
    public function fill($entity): void
    {
        $entity->setName($this->name);
        $entity->setCreatedAt(
            DateTimeImmutable::createFromFormat(
                'Y-m-d',
                $this->created_at,
            ),
        );
    }
}