<?php

namespace App\Repository\Contracts;

interface PostRepositoryInterface
{
    public function getFilteredPostsByUser(int $userId, array $filters = []);
    public function create(array $data);
    public function update(int $id, array $data);
    public function delete(int $id);
    public function getTodayPostsCount(int $userId);
    public function getPostById(int $id);
    public function getTotalPostsCount(int $userId);
    public function getScheduledPostsCount(int $userId);
    public function getPublishedPostsCount(int $userId);
    public function getUserPostsCountByPlatform(int $userId);
    public function getUserPostsStatusStats(int $userId);
}
