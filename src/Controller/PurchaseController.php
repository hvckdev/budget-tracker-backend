<?php

namespace App\Controller;

use App\Repository\PurchaseRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    public function __construct(
        private readonly Security $security,
        private readonly PurchaseRepository $purchaseRepository,
    ) {
    }

    #[Route('/purchase', name: 'app_purchase')]
    public function index(): JsonResponse
    {
        $purchases = $this->purchaseRepository->findByUser($this->security->getUser());

        return $this->json([
            'data' => $purchases
        ]);
    }
}
