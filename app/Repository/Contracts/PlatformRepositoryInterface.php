<?php

namespace App\Repository\Contracts;

interface PlatformRepositoryInterface
{
    public function all();
    public function getUserPlatforms(int $userId);
    public function userHasPlatform(int $userId, int $platformId);
    public function attachPlatform(int $userId, int $platformId);

    public function detachPlatform(int $userId, int $platformId);
    public function getPlatformById(int $id);
    public function getByIds(array $ids);
    public function getplatformsNotInUserPlatforms(int $userId, array $platformIds = []);
}
