<?php

namespace App\Service\Purchase;

use App\Entity\Product;
use App\Entity\Purchase;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;

class PurchaseProductService
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly ProductRepository $productRepository,
    ) {
    }

    public function syncProductsWithPurchase(Purchase $purchase, array $products): void
    {
        $this->removeOldProductsFromPurchase($purchase);
        $this->addProductsToPurchase($purchase, $products);
    }

    public function addProductsToPurchase(
        Purchase $purchase,
        array $products,
    ): void {
        foreach ($products as $product) {
            $entity = $this->createProduct($product);

            $entity->setUser($purchase->getUser());

            $purchase->addProduct($entity);
        }
    }

    private function removeOldProductsFromPurchase(Purchase $purchase): void
    {
        $products = $purchase->getProducts();

        foreach ($products as $product) {
            $purchase->removeProduct($product);
        }
    }

    private function createProduct(array $product): Product
    {
        $entity = $this->findProduct($product);

        if (! $entity) {
            $entity = new Product();
        }

        $entity->setName($product['name']);
        $entity->setPrice($product['price']);
        $entity->setCategory($this->categoryRepository->find($product['category']));

        return $entity;
    }

    private function findProduct(array $product): ?Product
    {
        return $this->productRepository->findBy([
            'name' => $product['name'],
        ])[0] ?? null;
    }
}