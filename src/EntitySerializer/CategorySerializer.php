<?php

namespace App\EntitySerializer;

use App\Entity\Category;

class CategorySerializer
{
    public function __construct(private readonly Category $category)
    {
    }

    public function serialize(): array
    {
        return [
            'name' => $this->category->getName(),
        ];
    }
}