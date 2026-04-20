<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthControllerTest extends WebTestCase
{
    public function testAuthSuccess(): void
    {
        $client = static::createClient();

        $client->jsonRequest('POST', '/api/v1/auth', [
            'username' => 'user@example.com',
            'password' => 'password123',
        ]);

        self::assertResponseIsSuccessful();

        $data = json_decode($client->getResponse()->getContent(), true);
        self::assertArrayHasKey('token', $data);
    }

    public function testAuthFailWrongPassword(): void
    {
        $client = static::createClient();

        $client->jsonRequest('POST', '/api/v1/auth', [
            'username' => 'user@example.com',
            'password' => 'wrongpassword',
        ]);

        self::assertResponseStatusCodeSame(401);
    }

    public function testAuthFailUserNotFound(): void
    {
        $client = static::createClient();

        $client->jsonRequest('POST', '/api/v1/auth', [
            'username' => 'notexist@test.com',
            'password' => 'password123',
        ]);

        self::assertResponseStatusCodeSame(401);
    }

    public function testAuthFailNotValidJson(): void
    {
        $client = static::createClient();

        $client->jsonRequest('POST', '/api/v1/auth', [
        ]);

        self::assertResponseStatusCodeSame(400);
    }
}
