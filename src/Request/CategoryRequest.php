<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints\NotBlank;

class CategoryRequest
{
    #[NotBlank]
    public string $name;
}