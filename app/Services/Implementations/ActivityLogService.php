<?php

namespace App\Services\Implementations;

use App\Repository\Contracts\ActivityLogRepositoryInterface;
use App\Services\Contracts\ActivityLogServiceInterface;

class ActivityLogService implements ActivityLogServiceInterface
{
    protected $activityLogRepo;
    public function __construct(ActivityLogRepositoryInterface $activityLogRepo)
    {
        $this->activityLogRepo = $activityLogRepo;
    }
    public function getLogs(int $userId)
    {
        return $this->activityLogRepo->getLog($userId);
    }
}
