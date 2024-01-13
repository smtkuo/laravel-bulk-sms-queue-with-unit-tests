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
    public function it_should_call_register_on_auth_service()
    {
        $request = new Request([
            'name' => 'John Doe',
            'email' => 'johndoe2@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123'
        ]);
        $this->authServiceMock->expects($this->once())
            ->method('register')
            ->with($request->all());

        $this->authController->register($request);
    }

    /** @test */
    public function it_should_call_login_on_auth_service()
    {
        $request = new Request([
            'email' => 'johndoe@example.com',
            'password' => 'Password123'
        ]);
        $this->authServiceMock->expects($this->once())
            ->method('login')
            ->with([
                'email' => 'johndoe@example.com',
                'password' => 'Password123'
            ]);

        $this->authController->login($request);
    }

     /** @test */
     public function authenticated_user_can_get_their_details()
     {
         $user = User::factory()->create();
         $token = JWTAuth::fromUser($user);
         $response = $this->withHeaders([
             'Authorization' => 'Bearer ' . $token,
         ])->get('/api/user-details');
 
         // Yanıtı doğrula
         $response->assertStatus(200);
     }
}
