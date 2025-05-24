<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Platform;
use Illuminate\Http\Request;
use App\Enums\PostStatus;
use Illuminate\Http\JsonResponse;
use App\Services\Contracts\PostServiceInterface;
use App\Services\Contracts\PlatformServiceInterface;

class DashboardController extends Controller
{
    protected $postService;
    protected $platformService;

    public function __construct(
        PostServiceInterface $postService,
        PlatformServiceInterface $platformService
    ) {
        $this->postService = $postService;
        $this->platformService = $platformService;
    }
    public function index()
    {
        $user = auth('web')->user();
        $stats = $this->postService->getStats($user->id);
        $connectedPlatforms = $this->platformService->listUserPlatforms($user->id)->count();

        $recentPosts = $this->postService->getPosts(['user_id' => $user->id]);
        $recentPosts = $recentPosts->take(5);

        $platformStats = $this->postService->getPlatformStats($user->id);
        $statusStats = $this->postService->getStatusStats($user->id);

        return view('dashboard', [
            'totalPosts' => $stats['totalPosts'],
            'scheduledPosts' => $stats['scheduledPosts'],
            'todayPostsCount' => $stats['todayPostsCount'],
            'connectedPlatforms' => $connectedPlatforms,
            'recentPosts' => $recentPosts,
            'platformStats' => $platformStats,
            'statusStats' => array_values($statusStats),
        ]);
    }
    public function filterPosts(Request $request)
    {
        $user = auth('web')->user();

        $posts = $this->postService->getPosts([
            'user_id' => $user->id,
            'status' => $request->status,
            'from_date' => $request->from,
            'to_date' => $request->to
        ]);
        $posts = $posts->take(5);
        return response()->json([
            'success' => true,
            'posts' => $posts
        ]);
    }
    public function getCalendarPosts(Request $request)
    {
        $user = auth('web')->user();

        $posts = $this->postService->getPosts([
            'user_id' => $user->id,
            'status' => PostStatus::SCHEDULED,
            'from_date' => $request->start,
            'to_date' => $request->end,
            'with' => ['platforms']
        ])->map(function ($post) {
            return [
                'id' => $post->id,
                'title' => $post->title,
                'start' => $post->scheduled_time,
                'platforms' => $post->platforms->pluck('name'),
                'status' => $post->status
            ];
        });

        return response()->json([
            'success' => true,
            'posts' => $posts
        ]);
    }
}
