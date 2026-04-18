<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private function authenticateUser(): array
    {
        $client = static::createClient();

        $client->jsonRequest('POST', '/api/v1/auth', [
            'username' => 'user@example.com',
            'password' => 'password123',
        ]);
        self::assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        return [
            'client' => $client,
            'token' => $data['token'],
        ];
    }

    public function testCurrentUserSuccess(): void
    {
        $auth = $this->authenticateUser();

        $auth['client']->request(
            'GET',
            '/api/v1/users/current',
            [],
            [],
            [
                'HTTP_AUTHORIZATION' => 'Bearer ' . $auth['token']
            ]
        );

        self::assertResponseIsSuccessful();

        $data = json_decode($auth['client']->getResponse()->getContent(), true);

        self::assertArrayHasKey('username', $data);
        self::assertArrayHasKey('roles', $data);
        self::assertArrayHasKey('balance', $data);
    }

    public function testCurrentUserUnauthorized(): void
    {
        $client = static::createClient();

        $client->request('GET', '/api/v1/users/current');

        self::assertResponseStatusCodeSame(401);
    }
}
