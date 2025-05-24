<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Platform;
use Illuminate\Http\Request;
use App\Enums\PostStatus;
use App\Http\Requests\Api\Post\StorePostRequest;
use App\Http\Requests\Api\Post\UpdatePostRequest;
use App\Repository\Contracts\PostRepositoryInterface;
use App\Services\Contracts\PostServiceInterface;
use App\Services\Contracts\PlatformServiceInterface;

class PostController extends Controller
{
    protected $postService;
    protected $platformService;
    protected $postRepo;
    public function __construct(
        PostServiceInterface $postService,
        PlatformServiceInterface $platformService,
        PostRepositoryInterface $postRepo
    ) {
        $this->platformService = $platformService;
        $this->postService = $postService;
        $this->postRepo = $postRepo;
    }
    public function index(Request $request)
    {
        $filters = [
            'status' => $request->status,
            'platform_id' => $request->platform,
        ];

        $posts = $this->postService->getPosts($filters);
        $platforms = Platform::orderBy('name')->get();

        return view('posts.index', compact('posts', 'platforms'));
    }

    public function create()
    {
        $user = auth('web')->user();
        $userPlatforms = $this->platformService->listUserPlatforms($user->id);

        return view('posts.create', compact('userPlatforms'));
    }

    public function store(StorePostRequest $request)
    {
        $result = $this->postService->create([
            'title'           => $request->title,
            'content'         => $request->content,
            'image_url'       => $request->image_url ?? null,
            'status'          => $request->status,
            'scheduled_time'  => $request->scheduled_time,
            'platform_ids'    => $request->platform_ids,
        ]);
        if (!$result['success'])
            return redirect()->back()->with('error', $result['message']);


        return redirect()->route('web.posts.index')->with('success', $result['message']);
    }

    public function edit(int $id)
    {
        $user = auth('web')->user();
        $post = $this->postRepo->getPostById($id);
        $userPlatforms = $this->platformService->listUserPlatforms($user->id);

        return view('posts.edit', compact('post', 'userPlatforms'));
    }

    public function update(UpdatePostRequest $request, int $id)
    {
        $result = $this->postService->update($id, [
            'title'           => $request->title,
            'content'         => $request->content,
            'image_url'       => $request->image_url,
            'status'          => $request->status,
            'scheduled_time'  => $request->scheduled_time,
            'platform_ids'    => $request->platform_ids,
        ]);

        if (!$result['success'])
            return redirect()->back()->with('error', $result['message']);

        return redirect()->route('web.posts.index')
            ->with('success', $result['message']);
    }

    public function destroy(int $id)
    {
        $result = $this->postService->delete($id);
        if (!$result['success'])
            return redirect()->back()->with('error', $result['message']);

        return redirect()->route('web.posts.index')->with('success', $result['message']);
    }
}
