<?php

namespace App\Helpers;

use App\Models\ActivityLog;

class LogHelpers
{
    public static function log(string $action, ?string $details = null)
    {
        if (auth('web')->check() || auth('api')->check()) {
            ActivityLog::create([
                'user_id' => auth('web')->id() ?? auth('api')->id(),
                'action'  => $action,
                'details' => $details,
            ]);
        }
    }
}
