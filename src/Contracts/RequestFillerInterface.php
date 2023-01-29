<?php

namespace App\Contracts;

interface RequestFillerInterface
{
    public function fill($entity): void;
}