<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\AuthService;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControllerTest extends TestCase
{
    private $authServiceMock;

    private $authController;

    public function setUp(): void
    {
        parent::setUp();
        $this->authServiceMock = $this->createMock(AuthService::class);
        $this->authController = new AuthController($this->authServiceMock);
    }

    /** @test */
    public function it_should_call_register_on_auth_service(): void
    {
        $request = new Request([
            'name' => 'Samet',
            'email' => 'test@mail.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123'
        ]);
        $this->authServiceMock->expects($this->once())
            ->method('register')
            ->with($request->all());
        $this->authController->register($request);
    }

    /** @test */
    public function it_should_call_login_on_auth_service(): void
    {
        $request = new Request([
            'email' => 'test@mail.com',
            'password' => 'Password123'
        ]);
        $this->authServiceMock->expects($this->once())
            ->method('login')
            ->with([
                'email' => 'test@mail.com',
                'password' => 'Password123'
            ]);
        $this->authController->login($request);
    }

     /** @test */
     public function authenticated_user_can_get_their_details(): void
     {
         $user = User::factory()->create();
         $token = JWTAuth::fromUser($user);
         $response = $this->withHeaders([
             'Authorization' => 'Bearer ' . $token,
         ])->get('/api/user-details');
         $response->assertStatus(200);
     }
}
