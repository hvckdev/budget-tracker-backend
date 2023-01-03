<?php

namespace App\Resource;

use App\Entity\Category;

class CategoryResource
{
    public function __construct(private readonly Category $category)
    {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->category->getName(),
        ];
    }
}