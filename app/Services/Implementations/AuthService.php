<?php

namespace App\Services\Implementations;

use App\Helpers\LogHelpers;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{
    protected $userRepo;
    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }
    public function register(array $data)
    {
        $data["password"] = Hash::make($data["password"]);

        $user = $this->userRepo->create($data);

        $token = $user->createToken('auth_token')->plainTextToken;

        return ['success' => true, 'message' => __('message.Registerd Successfully'), 'user' => $user, 'token' => $token];
    }
    public function login(string $email, string $password)
    {
        $user = $this->userRepo->findByEmail($email);
        if (!$user)
            return ['success' => false, 'message' => __('message.Email Not Found')];

        if (!Hash::check($password, $user->password))
            return ['success' => false, 'message' => __('message.Incorrect Password')];

        $token = $user->createToken('auth_token')->plainTextToken;

        return ['success' => true, 'message' => __('message.Login Successfully'), 'user' => $user, 'token' => $token];
    }
    public function getProfile()
    {
        $user = auth('api')->user();

        return ['success' => true, 'message' => __('message.Success'), 'user' => $user];
    }
    public function updateProfile(int $id, array $data)
    {
        $user = auth('api')->user();

        $data['password'] = $data['password'] ? Hash::make($data['password']) : $user->password;

        $this->userRepo->update($id, $data);
        LogHelpers::log('Update Profile');
        return ['success' => true, 'message' => __('message.Success'), 'user' => $user];
    }
    public function logout()
    {
        auth('api')->user()->tokens()->delete();
        return ['success' => true, 'message' => __('message.Logout Successfully')];
    }
}
