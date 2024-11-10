<?php

namespace App\Http\Controllers\Api\User\Auth;

use App\Base\Traits\Response\ApiResponseTrait;
use App\Http\Requests\Api\User\Auth\LoginRequest;
use App\Http\Requests\Api\User\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\UserAuthService;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;

class AuthController
{
    use ApiResponseTrait;

    private $UserAuthService;
    private $UserService;

    private string $modelResource = UserResource::class;

    public function __construct(UserAuthService $UserAuthService, UserService $UserService)
    {
        $this->UserAuthService = $UserAuthService;
        $this->UserService = $UserService;
    }

    public function login(LoginRequest $request)
    {
        $user = $this->UserAuthService->login($request->validated());

        $data = [
            'access_token' => $this->UserAuthService->createToken($user),
            'user' => new $this->modelResource($user),
        ];

        return $this->respondWithSuccess(__('auth.successfully_login'), $data);
    }

    public function Register(RegisterRequest $request)
    {
        $user = $this->UserAuthService->register($request->validated());
        $data = [
            'access_token' => $this->UserAuthService->createToken($user),
            'user' => new $this->modelResource($user),
        ];

        return $this->respondWithSuccess(__('auth.successfully_login'), $data);
    }

    public function logout()
    {
        Auth::guard('user-api')->user()->tokens()->delete();
        return $this->respondWithSuccess(__('auth.successfully_logout'));
    }
}
