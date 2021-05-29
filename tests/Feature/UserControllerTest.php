<?php

namespace Tests\Feature;


use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testValidRegistration()
    {
        $response = $this->json('POST', '/api/user/register', [
            'first_name' => 'test',
            'last_name' => 'user',
            'email' => 'my@email.com',
            'gender' => 'male',
            'birthdate' => '1990-05-06',
            'password' => '12345678',
        ]);

        $response->assertResponseOk();

        $responseText = $response->response->content();

        $this->assertJson($responseText);
    }

    public function testInvalidRegistration()
    {
        $response = $this->json('POST', '/api/user/register', [
            'first_name' => 'test',
            'last_name' => null,
            'email' => 'invalid email',
            'gender' => 'unknown',
            'birthdate' => null,
            'password' => '12345'
        ]);

        $response->assertResponseStatus(400);

        $responseText = $response->response->content();

        $this->assertJson($responseText);

        $responseArray = json_decode($responseText, true);

        $this->assertArrayHasKey('email', $responseArray['message']);
        $this->assertArrayHasKey('last_name', $responseArray['message']);
        $this->assertArrayHasKey('gender', $responseArray['message']);
        $this->assertArrayHasKey('birthdate', $responseArray['message']);
        $this->assertArrayHasKey('password', $responseArray['message']);
    }
}
