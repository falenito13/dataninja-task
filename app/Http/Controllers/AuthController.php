<?php

namespace App\Http\Controllers;

use App\DTOs\Auth\LoginDTO;
use App\DTOs\Auth\RegisterDTO;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\UserToken;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AuthController extends Controller
{

    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
        $this->authorizeResource(UserToken::class, 'userToken');
    }

    public function store(): JsonResponse
    {
        $generateTokenResponse = $this->authService->generateToken();
        return response()->json($generateTokenResponse)->setStatusCode(Response::HTTP_CREATED);
    }

    public function destroy(UserToken $userToken): JsonResponse
    {
        $deleteTokenResponse = $this->authService->deleteToken($userToken);
        return response()->json($deleteTokenResponse)->setStatusCode(Response::HTTP_NO_CONTENT);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $loginResponse = $this->authService->login(
            new LoginDTO($request->email, $request->password)
        );
        return response()->json($loginResponse);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        $registerResponse = $this->authService->register(
            new RegisterDTO($request->name, $request->email, $request->password)
        );
        return response()->json($registerResponse)->setStatusCode(Response::HTTP_CREATED);
    }
}
