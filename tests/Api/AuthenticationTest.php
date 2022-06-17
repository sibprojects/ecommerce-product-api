<?php
// tests/AuthenticationTest.php

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;
//use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class AuthenticationTest extends ApiTestCase
{
    //    use ReloadDatabaseTrait;

    public function testLogin(): void
    {
        $client = self::createClient();

        $user = new User();
        $user->setEmail('test@example.com');
        $user->setPassword(
            self::getContainer()->get('security.user_password_hasher')->hashPassword($user, '$3CR3T')
        );

        $manager = self::getContainer()->get('doctrine')->getManager();
        $manager->persist($user);
        $manager->flush();

        // retrieve a token
        $response = $client->request('POST', '/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'test@example.com',
                'password' => '$3CR3T',
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);

        $newProductData =[
            'name' => 'Toyota',
            'description' => 'Corolla',
            'price' => '25000.00',
            'vat' => 22,
        ];
        // test not authorized
        $client->request('POST', '/api/productCreate', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization: Bearer EmptyToken',
            ],
            'json' => $newProductData,
        ]);
        $this->assertResponseStatusCodeSame(401);

        // test authorized
        $client->request('POST', '/api/productCreate', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization: Bearer '.$json['token'],
            ],
            'json' => $newProductData,
         ]);
        $this->assertResponseIsSuccessful();
    }
}