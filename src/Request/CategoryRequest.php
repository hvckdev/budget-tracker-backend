<?php

namespace App\Request;

use App\Entity\Category;
use App\Contracts\RequestFillerInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryRequest implements RequestFillerInterface
{
    #[NotBlank]
    public string $name;

    /**
     * @param Category $entity
     *
     * @return void
     */
    public function fill($entity): void
    {
        $entity->setName($this->name);
    }
}