<?php

namespace App\Repository\Contracts;

interface ActivityLogRepositoryInterface
{
    public function getLog(int $userId);
}
