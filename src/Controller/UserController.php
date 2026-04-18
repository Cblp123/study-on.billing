<?php

namespace App\Controller;

use App\Entity\User;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

final class UserController extends AbstractController
{
    #[OA\Get(
        path: '/api/v1/users/current',
        summary: 'Получить текущего пользователя',
        security: [['Bearer' => []]],
        tags: ['User'],
        responses: [
            new OA\Response(response: 200, description: 'Данные пользователя'),
            new OA\Response(response: 401, description: 'Не авторизован'),
        ]
    )]
    #[Route('/api/v1/users/current', name: 'api_users_current', methods: ['GET'])]
    public function current(#[CurrentUser] User $user): JsonResponse
    {
        return $this->json([
            'username' => $user->getEmail(),
            'roles'    => $user->getRoles(),
            'balance'  => $user->getBalance(),
        ]);
    }
}
