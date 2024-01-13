<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class SmsRoutesTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    private function authenticate(): mixed
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        return $token;
    }

    public function testSendSmsRoute(): void
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/send-sms', [
            'phone_number' => '1234567890',
            'message' => 'Test message'
        ]);
        $response->assertStatus(200);
    }

    public function testSendBulkSmsRoute(): void
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/send-bulk-sms', [
            'messages' => [
                ['phone_number' => '1234567890', 'message' => 'Message 1'],
                ['phone_number' => '0987654321', 'message' => 'Message 2']
            ]
        ]);
        $response->assertStatus(200);
    }

    public function testSmsReportRoute(): void
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/sms-report');
        $response->assertStatus(200);
    }

    public function testSmsReportDetailsRoute(): void
    {
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/sms-report-details');
        $response->assertStatus(200);
    }
}
