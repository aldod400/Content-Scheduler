<?php

namespace App\Services\Contracts;

interface ActivityLogServiceInterface
{
    public function getLogs(int $userId);
}
