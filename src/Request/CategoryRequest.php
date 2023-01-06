<?php

namespace App\Request;

use App\Entity\Category;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryRequest
{
    #[NotBlank]
    public string $name;

    public function fill(Category $category): void
    {
        $category->setName($this->name);
    }
}