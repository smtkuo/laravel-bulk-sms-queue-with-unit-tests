<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Http\Request;
use App\Http\Controllers\SmsController;
use App\Services\SmsService;
use App\Models\User;
use App\Models\Sms;
use Illuminate\Database\Eloquent\Collection;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SmsControllerTest extends TestCase
{
    protected $smsServiceMock;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->smsServiceMock = Mockery::mock(SmsService::class);
        $this->user = User::factory()->make(['id' => 1]);
        $this->be($this->user);
    }

    public function testSend()
    {
        $request = Request::create('/api/send-sms', 'POST', [
            'phone_number' => '1234567890',
            'message' => 'Test message'
        ]);
        $request->setUserResolver(function () {
            return $this->user;
        });
        $smsMock = new Sms(['user_id' => $this->user->id, 'phone_number' => '1234567890', 'message' => 'Test message', 'status' => 'waiting', 'sent_at' => now()]);
        $this->smsServiceMock
            ->shouldReceive('sendSMS')
            ->once()
            ->withArgs([$this->user->id, '1234567890', 'Test message'])
            ->andReturn($smsMock);
        $controller = new SmsController($this->smsServiceMock);
        $response = $controller->send($request);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testSendBulkSms()
    {
        $user = $this->user;
        $messages = [
            ['phone_number' => '1234567890', 'message' => 'Message 1'],
            ['phone_number' => '0987654321', 'message' => 'Message 2']
        ];
        $request = Request::create('/api/send-bulk-sms', 'POST', ['messages' => $messages]);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        $this->smsServiceMock
            ->shouldReceive('sendBulkSMS')
            ->once()
            ->with($user->id, $messages)
            ->andReturnTrue();
        $controller = new SmsController($this->smsServiceMock);
        $response = $controller->sendBulk($request);
        $this->assertEquals(200, $response->getStatusCode());
        $responseContent = json_decode($response->getContent(), true);
        $this->assertEquals('Bulk SMS başarıyla gönderildi.', $responseContent['message']);
    }

    public function testGetSmsReport()
    {
        $user = $this->user;
        $parameters = [
            'status' => 'sent',
            'start_date' => '2024-01-01',
            'end_date' => '2024-01-31',
            'message' => 'Test'
        ];
        $request = Request::create('/api/sms-report', 'GET', $parameters);
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        $smsServiceMock = Mockery::mock(SmsService::class);
        $smsServiceMock->shouldReceive('getSmsReport')
            ->once()
            ->with($user->id, 'sent', '2024-01-01', '2024-01-31', 'Test')
            ->andReturn(new Collection());
        $controller = new SmsController($smsServiceMock);
        $response = $controller->getReport($request);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testGetDetail()
    {
        $user = $this->user;
        $expectedStatistics = [
            'total_sms' => 100,
            'delivered_sms' => 80,
            'failed_sms' => 20
        ];
        $smsServiceMock = Mockery::mock(SmsService::class);
        $smsServiceMock->shouldReceive('getDetail')
            ->once()
            ->with($user->id)
            ->andReturn($expectedStatistics);
        $controller = new SmsController($smsServiceMock);
        $request = Request::create('/api/sms-report-details', 'GET');
        $request->setUserResolver(function () use ($user) {
            return $user;
        });
        $response = $controller->getDetail($request);
        $this->assertEquals(200, $response->getStatusCode());
        $responseContent = json_decode($response->getContent(), true);
        $this->assertEquals($expectedStatistics, $responseContent['data']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
