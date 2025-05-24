<?php

namespace App\Repository\Eloquent;

use App\Models\User;
use App\Repository\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }
    public function create(array $data)
    {
        return User::create($data);
    }
    public function update(int $id, array $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);

        return $user;
    }
}
