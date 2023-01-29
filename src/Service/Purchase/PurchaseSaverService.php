<?php

namespace App\Service\Purchase;

use App\Entity\Purchase;
use Doctrine\Persistence\ManagerRegistry;

class PurchaseSaverService
{
    private ManagerRegistry $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function save(Purchase $purchase): void
    {
        $em = $this->managerRegistry->getManager();

        foreach ($purchase->getProducts() as $product) {
            $em->persist($product);
        }

        $em->persist($purchase);
        $em->flush();
    }
}