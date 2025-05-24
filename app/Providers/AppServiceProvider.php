<?php

namespace App\Providers;

use App\Repository\Contracts\ActivityLogRepositoryInterface;
use App\Repository\Eloquent\ActivityLogRepository;
use App\Repository\Contracts\PlatformRepositoryInterface;
use App\Repository\Contracts\PostRepositoryInterface;
use App\Repository\Contracts\UserRepositoryInterface;
use App\Repository\Eloquent\PlatformRepository;
use App\Repository\Eloquent\PostRepository;
use App\Repository\Eloquent\UserRepository;
use App\Services\Contracts\ActivityLogServiceInterface;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\PlatformServiceInterface;
use App\Services\Contracts\PostServiceInterface;
use App\Services\Implementations\ActivityLogService;
use App\Services\Implementations\AuthService;
use App\Services\Implementations\PlatformService;
use App\Services\Implementations\PostService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(PostServiceInterface::class, PostService::class);
        $this->app->bind(PlatformRepositoryInterface::class, PlatformRepository::class);
        $this->app->bind(PlatformServiceInterface::class, PlatformService::class);
        $this->app->bind(ActivityLogRepositoryInterface::class, ActivityLogRepository::class);
        $this->app->bind(ActivityLogServiceInterface::class, ActivityLogService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Response::macro(
            'api',
            function ($message, $statusCode = 200, $status = true, $errorNum = null, $data = null) {
                $responseData = [
                    'status' => $status,
                    'errorNum' => $errorNum,
                    'message' => $message,
                ];

                if ($data)
                    $responseData = array_merge($responseData, ['data' => $data]);

                return response()->json($responseData, $statusCode);
            }
        );
    }
}
