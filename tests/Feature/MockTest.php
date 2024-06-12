<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class MockTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        Http::fake([
            'http://somedomain.com' => Http::response(
                [
                    'name' => "Italy",
                    "code" => "IT"
                ],
                200
            )
        ]);

        $response = Http::get('http://somedomain.com');

        self::assertJsonStringEqualsJsonString(
            $response->body(),
            json_encode([
                'name' => "Italy",
                "code" => "IT"
            ])
        );
    }
}
