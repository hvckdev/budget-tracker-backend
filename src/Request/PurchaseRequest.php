<?php

namespace App\Request;

use DateTimeImmutable;
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
}