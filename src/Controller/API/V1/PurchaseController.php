<?php

namespace App\Controller\API\V1;

use App\Entity\Purchase;
use App\Request\PurchaseRequest;
use App\Resource\PurchaseResource;
use App\Repository\PurchaseRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Service\Purchase\PurchaseSaverService;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Purchase\PurchaseProductService;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Purchase\PurchaseCalculateAmountService;
use App\Service\EnhancedValidator\RequestValidatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/purchase')]
class PurchaseController extends AbstractController
{
    public function __construct(
        private readonly PurchaseRepository $purchaseRepository,
        private readonly RequestValidatorService $requestValidatorService,
        private readonly PurchaseProductService $purchaseProductService,
        private readonly PurchaseCalculateAmountService $purchaseCalculateAmountService,
        private readonly PurchaseSaverService $purchaseSaver
    ) {
    }

    #[Route('', name: 'app_purchase', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $purchases = $this->purchaseRepository->findByUser($this->getUser());

        return $this->json([
            'data' => $purchases,
        ]);
    }

    #[Route('', name: 'app_purchase_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $purchase = new Purchase();

        $purchase->setUser($this->getUser());

        return $this->savePurchase($request, $purchase);
    }

    #[Route('/{purchase}', name: 'app_purchase_read', methods: ['GET'])]
    public function read(Purchase $purchase): JsonResponse
    {
        $resource = new PurchaseResource($purchase);

        return $this->json([
            'data' => $resource->toArray(),
        ]);
    }

    #[Route('/{purchase}', name: 'app_purchase_update', methods: ['PUT'])]
    public function update(Request $request, Purchase $purchase): JsonResponse
    {
        return $this->savePurchase($request, $purchase);
    }

    #[Route('/{purchase}', name: 'app_purchase_delete', methods: ['DELETE'])]
    public function delete(Purchase $purchase): JsonResponse
    {
        $this->purchaseRepository->remove($purchase, true);

        return $this->json([
            'success' => true,
        ]);
    }

    private function savePurchase(Request $request, Purchase $purchase): JsonResponse
    {
        $validated = $this->requestValidatorService->validate(
            $request->getContent(),
            PurchaseRequest::class,
        );

        if (count($validated->getErrors()) > 0) {
            return $this->json($validated->getErrors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validated->getDto()->fill($purchase);

        $this->purchaseProductService->syncProductsWithPurchase($purchase, $validated->getDto()->products);

        $purchase->setAmount($this->purchaseCalculateAmountService->calculate($purchase));

        $this->purchaseSaver->save($purchase);

        return $this->json([
            'success' => true,
        ]);
    }
}
