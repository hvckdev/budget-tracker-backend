<?php

namespace App\Request;

use DateTimeImmutable;
use App\Entity\Purchase;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class PurchaseRequest
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

    public function fill(Purchase $purchase): void
    {
        $purchase->setName($this->name);
        $purchase->setCreatedAt(
            DateTimeImmutable::createFromFormat(
                'Y-m-d',
                $this->created_at,
            ),
        );
    }
}