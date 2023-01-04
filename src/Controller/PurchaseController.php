<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Product;
use App\Entity\Purchase;
use App\Request\PurchaseRequest;
use App\Repository\PurchaseRepository;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/purchase')]
class PurchaseController extends AbstractController
{
    public function __construct(
        private readonly Security $security,
        private readonly PurchaseRepository $purchaseRepository,
        private readonly SerializerInterface $serializer,
        private readonly ValidatorInterface $validator,
        private readonly CategoryRepository $categoryRepository,
        private readonly ManagerRegistry $managerRegistry,

    ) {
    }

    #[Route('', name: 'app_purchase', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $purchases = $this->purchaseRepository->findByUser($this->security->getUser());

        return $this->json([
            'data' => $purchases,
        ]);
    }

    #[Route('', name: 'app_purchase_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $em = $this->managerRegistry->getManager();

        $amount = "0.00";

        /** @var PurchaseRequest $dto */
        $dto = $this->serializer->deserialize($request->getContent(), PurchaseRequest::class, 'json');

        $errors = $this->validator->validate($dto);

        if ($errors->count() > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $purchase = new Purchase();

        $purchase->setUser($this->security->getUser());
        $purchase->setCreatedAt(
            DateTimeImmutable::createFromFormat(
                'Y-m-d',
                $dto->created_at,
            ),
        );
        $purchase->setName($dto->name);

        foreach ($dto->products as $product) {
            $entity = new Product();

            $entity->setUser($this->security->getUser());
            $entity->setName($product['name']);
            $entity->setPrice($product['price']);

            $entity->setCategory($this->categoryRepository->find($product['category']));

            $purchase->addProduct($entity);

            $em->persist($entity);

            $amount = bcadd($amount, $product['price'], 2);
        }

        $purchase->setAmount($amount);

        $em->persist($purchase);
        $em->flush();

        return $this->json([
            'success' => true,
        ]);
    }
}
