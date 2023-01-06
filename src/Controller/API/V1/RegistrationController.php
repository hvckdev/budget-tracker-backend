<?php

namespace App\Controller\API\V1;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Request\RegistrationRequest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/registration', name: 'app_registration', methods: ['POST'])]
    public function index(
        Request $request,
        SerializerInterface $serializer,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $hasher,
        UserRepository $userRepository
    ): JsonResponse {
        /** @var RegistrationRequest $dto */
        $dto = $serializer->deserialize($request->getContent(), RegistrationRequest::class, 'json');

        $errors = $validator->validate($dto);

        if ($errors->count() > 0) {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = new User();
        $user->setUsername($dto->username);

        $password = $hasher->hashPassword($user, $dto->password);

        $user->setPassword($password);

        $userRepository->save($user, true);

        return $this->json([
            'message' => 'Successfully registered!',
        ]);
    }
}
