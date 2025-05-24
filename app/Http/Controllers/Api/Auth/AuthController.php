<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    protected $authService;
    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }
    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password
        ]);
        return Response::api($result['message'], 200, true, null, ['user' => $result['user'], 'token' => $result['token']]);
    }
    public function login(LoginRequest $request)
    {
        $result = $this->authService
            ->login($request->email, $request->password);

        if (!$result['success'])
            return Response::api($result['message'], 400, false, 400);

        return Response::api($result['message'], 200, true, null, ['user' => $result['user'], 'token' => $result['token']]);
    }
    public function getProfile()
    {
        $result = $this->authService->getProfile();

        return Response::api($result['message'], 200, true, null, ['user' => $result['user']]);
    }
    public function updateProfile(UpdateProfileRequest $request)
    {
        $result = $this->authService
            ->updateProfile(auth('api')->user()->id, [
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $request->password
            ]);

        return Response::api($result['message'], 200, true, null, ['user' => $result['user']]);
    }
    public function logout()
    {
        $result = $this->authService->logout();
        return Response::api($result['message'], 200, true, null);
    }
}
