<?php

namespace App\Repository\Eloquent;

use App\Models\ActivityLog;
use App\Repository\Contracts\ActivityLogRepositoryInterface;

class ActivityLogRepository implements ActivityLogRepositoryInterface
{
    public function getLog(int $userId)
    {
        return ActivityLog::where('user_id', $userId)->get();
    }
}
