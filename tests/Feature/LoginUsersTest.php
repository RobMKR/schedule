<?php

namespace Tests\Feature;

use Tests\RegisterSuccessUser;
use Tests\TestCase;

class LoginUsersTest extends TestCase
{
    use RegisterSuccessUser;

    const URL = 'api/v1/auth/login';
    const METHOD = 'POST';
    const HEADERS = ['Accept' => 'application/json'];

    /**
     * Test case when no input is specified on login call
     */
    public function testRequiredFieldsForLogin()
    {
        $this->json(self::METHOD, self::URL, self::HEADERS)
            ->assertStatus(422)
            ->assertJson([
                "type" => "ERR_INVALID",
                "errors" => [
                    "email" => ["The email field is required."],
                    "password" => ["The password field is required."],
                ]
            ]);
    }

    /**
     * Test for wrong email/password pair
     */
    public function testEmailOrPasswordWrong()
    {
        $userDataWithWrongEmailOrPassword = [
            "email" => "wrongEmail",
            "password" => "demo12345"
        ];

        $this->json(self::METHOD, self::URL, $userDataWithWrongEmailOrPassword, self::HEADERS)
            ->assertStatus(422)
            ->assertJsonFragment(["type" => "ERR_MESSAGE"])
            ->assertJsonFragment(["error" => ERR_WRONG_CREDENTIALS])
            ->assertJsonFragment(["error_message" => "Login or password is incorrect"])
            ->assertJsonStructure([
                "type",
                "error",
                "error_message",
                "timestamp",
            ]);
    }

    /**
     * Test success login
     */
    public function testLoginSuccess()
    {
        $this->registerSuccessUser();

        $successUserData = [
            "email" => $this->adminEmail,
            "password" => $this->adminPassword
        ];

        $this->json(self::METHOD, self::URL, $successUserData, self::HEADERS)
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "token",
                    "expires_in",
                    "id"
                ],
                "metadata" => [
                    "me" => [
                        "id",
                        "name",
                        "email",
                        "role",
                        "created_at",
                        "updated_at",
                    ]
                ]
            ]);
    }
}
