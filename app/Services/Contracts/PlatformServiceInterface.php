<?php

namespace App\Services\Contracts;

interface PlatformServiceInterface
{
    public function listAvailable();
    public function listUserPlatforms(int $userId);
    public function togglePlatform(int $userId, int $platformId);
    public function getplatformsNotInUserPlatforms(int $userId, array $platformIds = []);
}
