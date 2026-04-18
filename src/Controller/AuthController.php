<?php

namespace App\Controller;

use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

class AuthController extends AbstractController
{
    #[OA\Post(
        path: '/api/v1/auth',
        summary: 'Авторизация пользователя',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'username', type: 'string', example: 'user@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password123'),
                ]
            )
        ),
        tags: ['Auth'],
        responses: [
            new OA\Response(response: 200, description: 'JWT токен'),
            new OA\Response(response: 401, description: 'Неверные данные'),
        ]
    )]
    #[Route('/api/v1/auth', name: 'api_auth', methods: ['POST'])]
    public function auth(): JsonResponse
    {
        return $this->json(['message' => '401 Unauthorized'], 401);
    }
}
