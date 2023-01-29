<?php

namespace App\Controller\API\V1;

use App\Entity\Category;
use App\Request\CategoryRequest;
use App\Resource\CategoryResource;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\EnhancedValidator\RequestValidatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/category')]
class CategoryController extends AbstractController
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository,
        private readonly RequestValidatorService $requestValidatorService,
    ) {
    }

    #[Route('', name: 'app_category', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $categories = $this->categoryRepository->findByUser($this->getUser());

        return $this->json([
            'data' => $categories,
        ]);
    }


    #[Route('', name: 'app_category_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $category = new Category();

        $category->setUser($this->getUser());

        $validated = $this->requestValidatorService->validate(
            $request->getContent(),
            CategoryRequest::class,
        );

        if (count($validated->getErrors()) > 0) {
            return $this->json($validated->getErrors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validated->getDto()->fill($category);

        $this->categoryRepository->save($category, true);

        return $this->json([
            'success' => true,
        ]);
    }

    #[Route('/{category}', name: 'app_category_update', methods: ['PUT'])]
    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $this->requestValidatorService->validate(
            $request->getContent(),
            CategoryRequest::class,
        );

        if (count($validated->getErrors()) > 0) {
            return $this->json($validated->getErrors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $validated->getDto()->fill($category);

        $this->categoryRepository->save($category, true);

        return $this->json([
            'success' => true,
        ]);
    }

    #[Route('/{category}', name: 'app_category_read', methods: ['GET'])]
    public function read(Category $category): JsonResponse
    {
        $resource = new CategoryResource($category);

        return $this->json(['data' => $resource->toArray()]);
    }

    #[Route('/{category}', name: 'app_category_delete', methods: ['DELETE'])]
    public function delete(Category $category): JsonResponse
    {
        $this->categoryRepository->remove($category, true);

        return $this->json([
            'success' => true,
        ]);
    }
}
