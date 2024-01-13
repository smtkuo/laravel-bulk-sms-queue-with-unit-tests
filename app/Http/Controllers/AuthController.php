<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiException;
use Illuminate\Http\Request;
use App\Services\AuthService;
use App\Helpers\ResponseHelper;
use App\Enums\HttpStatusCode;


class AuthController extends Controller
{

    public function __construct(
        protected AuthService $authService
    ) {
    }

    /**
     * @param Request $request
     * 
     * @return mixed
     * 
     * @OA\Post(
     *     path="/api/register",
     *     tags={"auth"},
     *     summary="Register a new user",
     *     operationId="register",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", format="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Password123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="Password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Successful registration",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     )
     * )
     */
    public function register(Request $request): mixed
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string|min:8'
            ]);
            $registerData = $this->authService->register($validatedData);

            return ResponseHelper::success($registerData, 'User registered successfully', HttpStatusCode::CREATED);
        } catch (ApiException $e) {
            return ResponseHelper::error($e->getMessage(), HttpStatusCode::BAD_REQUEST);
        }
    }



    /**
     * @param Request $request
     * 
     * @return mixed
     * 
     * @OA\Post(
     *     path="/api/login",
     *     tags={"auth"},
     *     summary="Authenticate user and return a token",
     *     operationId="login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="Password123"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful login",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials"
     *     )
     * )
     */
    public function login(Request $request): mixed
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);
            $loginData = $this->authService->login($credentials);

            return ResponseHelper::success($loginData, 'User registered successfully', code: HttpStatusCode::OK);
        } catch (ApiException $e) {
            return ResponseHelper::error($e->getMessage(), HttpStatusCode::UNAUTHORIZED);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/user-details",
     *     tags={"auth"},
     *     summary="Get User Details",
     *     description="Get details of the authenticated user",
     *     operationId="getUserDetails",
     *     security={
     *         {"bearerAuth": {}}
     *     },
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function getUserDetails(Request $request): mixed
    {
        try {
            $user = $request->get('user');

            return ResponseHelper::success(compact('user'), 'User details retrieved', HttpStatusCode::OK);
        } catch (ApiException $e) {
            return ResponseHelper::error($e->getMessage(), HttpStatusCode::FORBIDDEN);
        }
    }
}
