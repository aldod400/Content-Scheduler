<?php

namespace App\Services\Implementations;

use App\Helpers\ImageHelpers;
use App\Helpers\LogHelpers;
use App\Repository\Contracts\PlatformRepositoryInterface;
use App\Repository\Contracts\PostRepositoryInterface;
use App\Services\Contracts\PostServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class PostService implements PostServiceInterface
{
    public $postRepo;
    public $platformRepo;
    public function __construct(
        PostRepositoryInterface $postRepo,
        PlatformRepositoryInterface $platformRepo
    ) {
        $this->postRepo = $postRepo;
        $this->platformRepo = $platformRepo;
    }
    public function getPosts(array $filters = [])
    {
        $userId = auth('api')->user()->id ?? auth('web')->user()->id;

        $posts = $this->postRepo->getFilteredPostsByUser($userId, $filters);

        return $posts;
    }
    public function create(array $data)
    {
        $countTodayPosts = $this->postRepo
            ->getTodayPostsCount(auth('api')->user()->id);

        if ($countTodayPosts >= 10)
            return [
                'success' => false,
                'message' => __('message.You have reached the maximum number of posts per day.')
            ];

        $result = $this->validatePlatformConstraints($data);
        if (!$result['success'])
            return $result;

        if ($data['image_url'] != null)
            $data['image_url'] = ImageHelpers::addImage($data['image_url'], 'posts');

        $data['user_id'] = auth('api')->user()->id;
        DB::beginTransaction();

        try {
            $post = $this->postRepo->create($data);

            $post->platforms()->sync($data['platform_ids']);

            DB::commit();
            LogHelpers::log('Created Post', $post->title);
            $post->load('platforms');
            return [
                'success' => true,
                'message' => __('message.Post Created Successfully'),
                'post' => $post
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    public function update(int $id, array $data)
    {
        $post = $this->postRepo->getPostById($id);

        $user = auth('api')->user();

        if ($user->id !== $post->user_id)
            return [
                'success' => false,
                'message' => __('message.You are not authorized to edit this post')
            ];

        $result = $this->validatePlatformConstraints($data);
        if (!$result['success'])
            return $result;

        if ($post->status == 'published')
            return [
                'success' => false,
                'message' => __('message.Cannot edit published post')
            ];
        if (isset($data['image_url'])) {
            if (File::exists($post->image_url))
                File::delete($post->image_url);
            $data['image_url'] = ImageHelpers::addImage($data['image_url'], 'posts');
        }
        $platformIds = $data['platform_ids'] ?? [];
        DB::beginTransaction();
        try {
            $post = $this->postRepo->update($id, [
                'title'           => $data['title'] ?? $post->title,
                'content'         => $data['content'] ?? $post->content,
                'status'          => $data['status'] ?? $post->status,
                'scheduled_time'  => $data['scheduled_time'] ?? $post->scheduled_time,
                'image_url'       => $data['image_url'] ?? $post->image_url,
            ]);

            if (!empty($platformIds))
                $post->platforms()->sync($platformIds);

            DB::commit();
            LogHelpers::log('Updated Post', $post->title);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        $post->load('platforms');
        return [
            'success' => true,
            'message' => __('message.Post Updated Successfully'),
            'post' => $post->refresh(),
        ];
    }

    public function delete(int $id)
    {

        $post = $this->postRepo->getPostById($id);
        if ($post->user_id !== auth('api')->user()->id) {
            return [
                'success' => false,
                'message' => __('message.You are not authorized to delete this post')
            ];
        }

        if ($post->status === 'published') {
            return [
                'success' => false,
                'message' => __('message.Cannot delete published post')
            ];
        }
        $post = $this->postRepo->delete($id);
        LogHelpers::log('Deleted Post', $post->title);
        return [
            'success' => true,
            'message' => __('message.Post Deleted Successfully'),
        ];
    }
    public function getStats(int $userId): array
    {
        return [
            'totalPosts' => $this->postRepo->getTotalPostsCount($userId),
            'scheduledPosts' => $this->postRepo->getScheduledPostsCount($userId),
            'publishedPosts' => $this->postRepo->getPublishedPostsCount($userId),
            'todayPostsCount' => $this->postRepo->getTodayPostsCount($userId)
        ];
    }
    public function getPlatformStats($userId)
    {
        return $this->postRepo->getUserPostsCountByPlatform($userId);
    }

    public function getStatusStats($userId)
    {
        $statusStats = $this->postRepo->getUserPostsStatusStats($userId);

        $allStatuses = ['published', 'scheduled', 'draft'];
        foreach ($allStatuses as $status) {
            if (!isset($statusStats[$status])) {
                $statusStats[$status] = 0;
            }
        }

        return [
            'published' => $statusStats['published'],
            'scheduled' => $statusStats['scheduled'],
            'draft' => $statusStats['draft']
        ];
    }
    protected function validatePlatformConstraints(array $data)
    {
        $platforms = $this->platformRepo->getByIds($data['platform_ids']);
        $contentLength = strlen($data['content'] ?? '');

        foreach ($platforms as $platform) {
            $result = ['success' => true, 'message' => ''];

            switch ($platform->type) {
                case 'twitter':
                    if ($contentLength > 280) {
                        return [
                            'success' => false,
                            'message' => __('message.Twitter allows a maximum of 280 characters'),
                        ];
                    }
                    break;

                case 'linkedin':
                    if ($contentLength > 1300) {
                        return [
                            'success' => false,
                            'message' => __('message.LinkedIn allows a maximum of 1300 characters')
                        ];
                    }
                    break;

                case 'facebook':
                    if ($contentLength > 63206) {
                        return [
                            'success' => false,
                            'message' => __('message.Facebook allows a maximum of 63,206 characters')
                        ];
                    }
                    break;

                case 'instagram':
                    if ($contentLength > 2200) {
                        return [
                            'success' => false,
                            'message' => __('message.Instagram allows a maximum of 2,200 characters')
                        ];
                    }
                    break;

                case 'whatsapp':
                    if ($contentLength > 65536) {
                        return [
                            'success' => false,
                            'message' => __('message.WhatsApp allows a maximum of 65,536 characters')
                        ];
                    }
                    break;

                case 'telegram':
                    if ($contentLength > 4096) {
                        return [
                            'success' => false,
                            'message' => __('message.Telegram allows a maximum of 4,096 characters')
                        ];
                    }
                    break;

                case 'snapchat':
                    if ($contentLength > 80) {
                        return [
                            'success' => false,
                            'message' => __('message.Snapchat allows a maximum of 80 characters')
                        ];
                    }
                    break;
            }
        }

        return ['success' => true, 'message' => 'Content length is valid for all platforms'];
    }
}
