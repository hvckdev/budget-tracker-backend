<?php

namespace App\Controller;

use LogicException;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    #[Route('/user', name: 'app_user')]
    public function index(): JsonResponse
    {
        if (($user = $this->security->getUser()) && $user instanceof User) {
            return $this->json([
                'username' => $user->getUsername(),
            ]);
        }

        throw new LogicException('This exception should not be reached.');
    }
}
