<?php

namespace App\Services\Contracts;

interface AuthServiceInterface
{
    public function register(array $data);
    public function login(string $email, string $password);
    public function getProfile();
    public function updateProfile(int $id, array $data);
    public function logout();
}
