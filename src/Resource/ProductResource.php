<?php

namespace App\Resource;

use App\Entity\Product;

class ProductResource
{
    public function __construct(private readonly Product $product)
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->product->getId(),
            'name' => $this->product->getName(),
            'price' => $this->product->getPrice(),
            'category' => $this->product->getCategory()?->getId(),
        ];
    }
}