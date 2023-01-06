<?php

namespace App\Controller\API\V1;

use App\Entity\Purchase;
use App\Request\PurchaseRequest;
use App\Resource\PurchaseResource;
use App\Repository\PurchaseRepository;
use App\Service\RequestValidatorService;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Purchase\PurchaseProductService;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\Purchase\PurchaseCalculateAmountService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/purchase')]
class PurchaseController extends AbstractController
{
    public function __construct(
        private readonly PurchaseRepository $purchaseRepository,
        private readonly ManagerRegistry $managerRegistry,
        private readonly RequestValidatorService $requestValidatorService,
        private readonly PurchaseProductService $purchaseProductService,
        private readonly PurchaseCalculateAmountService $purchaseCalculateAmountService,
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
        $em = $this->managerRegistry->getManager();

        $purchase = new Purchase();

        $purchase->setUser($this->getUser());

        /** @var PurchaseRequest $dto */
        [$dto, $errors] = $this->requestValidatorService->validate(
            $request->getContent(),
            PurchaseRequest::class,
        );

        if ($errors->count() > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $dto->fill($purchase);

        $this->purchaseProductService->syncProductsWithPurchase($purchase, $dto->products);

        $purchase->setAmount($this->purchaseCalculateAmountService->calculate($purchase));

        foreach ($purchase->getProducts() as $product) {
            $em->persist($product);
        }

        $em->persist($purchase);
        $em->flush();

        return $this->json([
            'success' => true,
        ]);
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
        $em = $this->managerRegistry->getManager();

        /** @var PurchaseRequest $dto */
        [$dto, $errors] = $this->requestValidatorService->validate(
            $request->getContent(),
            PurchaseRequest::class,
        );

        if ($errors->count() > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $dto->fill($purchase);

        $this->purchaseProductService->syncProductsWithPurchase($purchase, $dto->products);

        $purchase->setAmount($this->purchaseCalculateAmountService->calculate($purchase));

        foreach ($purchase->getProducts() as $product) {
            $em->persist($product);
        }

        $em->persist($purchase);
        $em->flush();

        return $this->json([
            'success' => true,
        ]);
    }

    #[Route('/{purchase}', name: 'app_purchase_delete', methods: ['DELETE'])]
    public function delete(Purchase $purchase): JsonResponse
    {
        $this->purchaseRepository->remove($purchase, true);

        return $this->json([
            'success' => true,
        ]);
    }
}
