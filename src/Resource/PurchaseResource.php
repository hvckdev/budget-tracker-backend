<?php

namespace App\Resource;

use App\Entity\Purchase;
use App\ResourceCollection\ProductResourceCollection;

class PurchaseResource
{
    public function __construct(private readonly Purchase $purchase)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->purchase->getId(),
            'name' => $this->purchase->getName(),
            'created_at' => $this->purchase->getCreatedAt(),
            'amount' => $this->purchase->getAmount(),
            'products' => (new ProductResourceCollection($this->purchase->getProducts()))->toArray(),
        ];
    }
}