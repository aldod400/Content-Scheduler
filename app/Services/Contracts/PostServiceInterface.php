<?php

namespace App\Services\Contracts;

interface PostServiceInterface
{
    public function getPosts(array $filters = []);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getStats(int $userId);
    public function getPlatformStats(int $userId);
    public function getStatusStats(int $userId);
}
