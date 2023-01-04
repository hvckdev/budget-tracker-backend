<?php

namespace App\ResourceCollection;

use App\Resource\ProductResource;
use Doctrine\Common\Collections\Collection;

class ProductResourceCollection
{
    public function __construct(private readonly Collection $collection)
    {
    }

    public function toArray($startWith = 1): array
    {
        $result = [];

        foreach ($this->collection as $product) {
            $resource = new ProductResource($product);

            $result[$startWith++] = $resource->toArray();
        }

        return $result;
    }
}