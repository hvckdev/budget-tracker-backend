<?php

namespace App\Controller;

use App\Entity\Category;
use App\Request\CategoryRequest;
use App\Repository\CategoryRepository;
use Symfony\Bundle\SecurityBundle\Security;
use App\EntitySerializer\CategorySerializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

#[Route('/category')]
class CategoryController extends AbstractController
{
    public function __construct(
        readonly SerializerInterface $serializer,
        readonly ValidatorInterface $validator,
        readonly Security $security,
        readonly CategoryRepository $categoryRepository
    ) {
    }

    #[Route('', name: 'app_category', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $categories = $this->categoryRepository->findByUser($this->security->getUser());

        return $this->json([
            'categories' => $categories,
        ]);
    }


    #[Route('', name: 'app_category_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        /** @var CategoryRequest $dto */
        $dto = $this->serializer->deserialize($request->getContent(), CategoryRequest::class, 'json');

        $errors = $this->validator->validate($dto);

        if ($errors->count() > 0) {
            throw new UnprocessableEntityHttpException((string) $errors);
        }

        $category = new Category();

        $category->setName($dto->name);
        $category->setUser($this->security->getUser());

        $this->categoryRepository->save($category, true);

        return $this->json([
            'success' => true,
        ]);
    }

    #[Route('/{category}', name: 'app_category_update', methods: ['PUT'])]
    public function update(Request $request, Category $category): JsonResponse
    {
        /** @var CategoryRequest $dto */
        $dto = $this->serializer->deserialize($request->getContent(), CategoryRequest::class, 'json');

        $errors = $this->validator->validate($dto);

        if ($errors->count() > 0) {
            throw new UnprocessableEntityHttpException((string) $errors);
        }

        $category->setName($dto->name);

        $this->categoryRepository->save($category, true);

        return $this->json([
            'success' => true,
        ]);
    }

    #[Route('/{category}', name: 'app_category_read', methods: ['GET'])]
    public function read(Category $category): JsonResponse
    {
        $serializer = new CategorySerializer($category);

        return $this->json($serializer->serialize());
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
