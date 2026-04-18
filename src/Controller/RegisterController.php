<?php

namespace App\Controller;

use App\Dto\RegisterUserDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegisterController extends AbstractController
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
        private EntityManagerInterface $entityManager,
        private JWTTokenManagerInterface $jwtManager
    ) {
    }

    #[OA\Post(
        path: '/api/v1/register',
        summary: 'Регистрация пользователя',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'email', type: 'string', example: 'user@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password123'),
                ]
            )
        ),
        tags: ['Auth'],
        responses: [
            new OA\Response(response: 201, description: 'Пользователь создан'),
            new OA\Response(response: 400, description: 'Ошибка валидации'),
        ]
    )]
    #[Route('/api/v1/register', name: 'register', methods: ['POST'])]
    public function register(#[MapRequestPayload] RegisterUserDto $dto): JsonResponse
    {
        $user = new User();
        $user->setEmail($dto->email);
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->hasher->hashPassword($user, $dto->password));
        $user->setbalance(0);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $this->json([
            'token' => $this->jwtManager->create($user),
            'roles' => $user->getRoles(),
        ], Response::HTTP_CREATED);
    }
}
