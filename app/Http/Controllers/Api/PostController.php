<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\PostFilterRequest;
use App\Http\Requests\Api\Post\StorePostRequest;
use App\Http\Requests\Api\Post\UpdatePostRequest;
use App\Services\Contracts\PostServiceInterface;
use Illuminate\Http\Response;

class PostController extends Controller
{
    public $postService;
    public function __construct(PostServiceInterface $postService)
    {
        $this->postService = $postService;
    }
    public function index(PostFilterRequest $request)
    {
        $posts = $this->postService->getPosts([
            'status' => $request->status,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date
        ]);

        return Response::api(__('message.Success'), 200, true, null, ['posts' => $posts]);
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
            return Response::api($result['message'], 400, false, 400);

        return Response::api($result['message'], 200, true, null, ['post' => $result['post']]);
    }
    public function update(UpdatePostRequest $request, int $id)
    {
        $result = $this->postService->update($id, [
            'title'           => $request->title,
            'content'         => $request->content,
            'image_url'       => $request->image_url ?? null,
            'status'          => $request->status,
            'scheduled_time'  => $request->scheduled_time,
            'platform_ids'    => $request->platform_ids,
        ]);
        if (!$result['success'])
            return Response::api($result['message'], 400, false, 400);
        return Response::api($result['message'], 200, true, null, ['post' => $result['post']]);
    }
    public function destroy(int $id)
    {
        $result = $this->postService->delete($id);
        if (!$result['success'])
            return Response::api($result['message'], 400, false, 400);
        return Response::api($result['message'], 200, true, null);
    }
}
