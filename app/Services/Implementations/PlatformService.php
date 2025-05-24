<?php

namespace App\Services\Implementations;

use App\Repository\Contracts\PlatformRepositoryInterface;
use App\Services\Contracts\PlatformServiceInterface;

class PlatformService implements PlatformServiceInterface
{
    public $platformRepo;
    public function __construct(PlatformRepositoryInterface $platformRepo)
    {
        $this->platformRepo = $platformRepo;
    }
    public function listAvailable()
    {
        return $this->platformRepo->all();
    }
    public function listUserPlatforms(int $userId)
    {
        return $this->platformRepo->getUserPlatforms($userId);
    }
    public function togglePlatform(int $userId, int $platformId)
    {
        $this->platformRepo->getPlatformById($platformId);

        if ($this->platformRepo->userHasPlatform($userId, $platformId)) {
            $this->platformRepo->detachPlatform($userId, $platformId);
            return false;
        } else {
            $this->platformRepo->attachPlatform($userId, $platformId);
            return true;
        }
    }
    public function getplatformsNotInUserPlatforms(int $userId, array $platformIds = [])
    {
        return $this->platformRepo->getplatformsNotInUserPlatforms($userId, $platformIds);
    }
}
