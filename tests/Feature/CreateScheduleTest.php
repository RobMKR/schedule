<?php

namespace Tests\Feature;

use App\Services\Schedule\ScheduleService;
use Tests\GetAuthToken;
use Tests\TestCase;

class CreateScheduleTest extends TestCase
{
    use GetAuthToken;

    const URL = 'api/v1/schedule';
    const METHOD = 'POST';
    const HEADERS = ['Accept' => 'application/json'];

    /**
     * Test case when no input is specified on login call
     */
    public function testEmptyParams()
    {
        $headers = array_merge(self::HEADERS, [
            'Authorization' => 'Bearer ' . $this->getToken()
        ]);

        $data = [

        ];

        $this->json(self::METHOD, self::URL, $data, $headers)
            ->assertStatus(422)
            ->assertJson([
                "type" => "ERR_INVALID",
                "errors" => [
                    "userId" => ["The user id field is required."],
                    "date" => ["The date field is required."],
                    "hours" => ["The hours field is required."],
                ]
            ]);
    }

    public function testSuccess()
    {
        $headers = array_merge(self::HEADERS, [
            'Authorization' => 'Bearer ' . $this->getToken()
        ]);

        $data = [
            "userId" => $this->getId(),
            "date" => "2022-01-01",
            "hours" => 10,
        ];

        $this->json(self::METHOD, self::URL, $data, $headers)
            ->assertStatus(200)
            ->assertJsonStructure([
                "data" => [
                    "user_id",
                    "date",
                    "hours",
                    "updated_at",
                    "created_at",
                    "id",
                ],
                "metadata" => []
            ]);

    }
}
