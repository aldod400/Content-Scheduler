<?php

namespace App\Repository\Eloquent;

use App\Models\Platform;
use App\Models\User;
use App\Models\UserPlatform;
use App\Repository\Contracts\PlatformRepositoryInterface;

class PlatformRepository implements PlatformRepositoryInterface
{
    public function all()
    {
        return Platform::all();
    }
    public function getUserPlatforms(int $userId)
    {
        return User::findOrfail($userId)->platforms()->get();
    }
    public function userHasPlatform(int $userId, int $platformId)
    {
        return User::findOrFail($userId)->platforms()->where('platform_id', $platformId)->exists();
    }

    public function attachPlatform(int $userId, int $platformId)
    {
        User::findOrFail($userId)->platforms()->attach($platformId);
    }

    public function detachPlatform(int $userId, int $platformId)
    {
        User::findOrFail($userId)->platforms()->detach($platformId);
    }
    public function getPlatformById(int $id)
    {
        return Platform::findOrFail($id);
    }
    public function getByIds(array $ids)
    {
        return Platform::whereIn('id', $ids)->get();
    }
    public function getplatformsNotInUserPlatforms(int $userId, array $platformIds = [])
    {
        return Platform::query()
            ->whereNotIn('id', function ($query) use ($userId) {
                $query->select('platform_id')
                    ->from('user_platforms')
                    ->where('user_id', $userId);
            })
            ->orderBy('name')
            ->get();
    }
}
