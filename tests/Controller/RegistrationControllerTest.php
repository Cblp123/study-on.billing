<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RegistrationControllerTest extends WebTestCase
{
    private function registerUser(array $data = []): object
    {
        $client = static::createClient();

        $client->jsonRequest('POST', '/api/v1/register', array_merge([
            'email' => 'newuser@test.com',
            'password' => 'password123',
        ], $data));

        return $client;
    }

    public function testRegisterReturnsOk(): void
    {
        $this->registerUser();

        self::assertResponseStatusCodeSame(201);
    }

    public function testRegisterReturnsValidJson(): void
    {
        $client = $this->registerUser();

        $data = json_decode($client->getResponse()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        self::assertIsArray($data);
        self::assertArrayHasKey('token', $data);
        self::assertArrayHasKey('roles', $data);

        self::assertIsString($data['token']);
        self::assertIsArray($data['roles']);

        self::assertNotSame('', $data['token']);
        self::assertContains('ROLE_USER', $data['roles']);
    }

    public function testRegisterFailDuplicateEmail(): void
    {
        $this->registerUser([
            'email' => 'user@example.com'
        ]);

        self::assertResponseStatusCodeSame(422);
    }

    public function testRegisterFailInvalidEmail(): void
    {
        $this->registerUser([
            'email' => 'invalid-email'
        ]);

        self::assertResponseStatusCodeSame(422);
    }

    public function testRegisterFailShortPassword(): void
    {
        $this->registerUser([
            'password' => '123'
        ]);

        self::assertResponseStatusCodeSame(422);
    }
}
