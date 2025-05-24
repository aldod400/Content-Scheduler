<?php

namespace App\Repository\Eloquent;

use App\Models\Post;
use App\Repository\Contracts\PostRepositoryInterface;
use App\Enums\PostStatus;
use App\Models\Platform;

class PostRepository implements PostRepositoryInterface
{
    public function getFilteredPostsByUser(int $userId, array $filters = [])
    {
        return Post::where('user_id', $userId)
            ->when(isset($filters['status']), fn($q) => $q->where('status', $filters['status']))
            ->when(isset($filters['from_date']), fn($q) => $q->whereDate('scheduled_time', '>=', $filters['from_date']))
            ->when(isset($filters['to_date']), fn($q) => $q->whereDate('scheduled_time', '<=', $filters['to_date']))
            ->when(isset($filters['platform_id']), function ($query) use ($filters) {
                return $query->whereRelation('platforms', 'platforms.id', $filters['platform_id']);
            })
            ->with('platforms')
            ->orderBy('created_at', 'desc')
            ->get();
    }
    public function create(array $data)
    {
        return Post::create($data);
    }
    public function update(int $id, array $data)
    {
        $post = Post::findOrFail($id);

        $post->update($data);

        return $post;
    }
    public function delete(int $id)
    {
        $post = Post::findOrFail($id);

        $post->delete();

        return $post;
    }
    public function getTodayPostsCount(int $userId)
    {
        return Post::where('user_id', $userId)
            ->whereDate('created_at', now()->toDateString())
            ->count();
    }
    public function getPostById(int $id)
    {
        return Post::findOrFail($id);
    }
    public function getTotalPostsCount(int $userId): int
    {
        return Post::where('user_id', $userId)->count();
    }
    public function getScheduledPostsCount(int $userId): int
    {
        return Post::where('user_id', $userId)
            ->where('status', PostStatus::SCHEDULED)
            ->count();
    }
    public function getPublishedPostsCount(int $userId): int
    {
        return Post::where('user_id', $userId)
            ->where('status', PostStatus::PUBLISHED)
            ->count();
    }
    public function getUserPostsCountByPlatform($userId)
    {
        return Platform::withCount(['posts' => function ($query) use ($userId) {
            $query->where('user_id', $userId);
        }])->get();
    }

    public function getUserPostsStatusStats($userId)
    {
        return Post::where('user_id', $userId)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }
}
