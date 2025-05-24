<?php

namespace App\Repository\Contracts;

Interface UserRepositoryInterface
{
    public function findByEmail(string $email);
    public function create(array $data);
    public function update(int $id, array $data);
}
