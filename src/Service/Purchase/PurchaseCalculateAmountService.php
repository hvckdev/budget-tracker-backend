<?php

namespace App\Service\Purchase;

use App\Entity\Purchase;

class PurchaseCalculateAmountService
{
    public function calculate(Purchase $purchase): int|string
    {
        $amount = 0;

        foreach ($purchase->getProducts() as $product) {
            $amount = bcadd($amount, $product->getPrice(), 2);
        }

        return $amount;
    }
}