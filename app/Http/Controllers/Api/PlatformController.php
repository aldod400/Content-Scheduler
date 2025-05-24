<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Contracts\PlatformServiceInterface;
use Illuminate\Http\Response;

class PlatformController extends Controller
{
    public $platformService;
    public function __construct(PlatformServiceInterface $platformService)
    {
        $this->platformService = $platformService;
    }
    public function index()
    {
        $platforms = $this->platformService->listAvailable();

        return Response::api(
            __('message.Available platforms retrieved successfully'),
            200,
            true,
            null,
            ['platforms' => $platforms]
        );
    }
    public function myPlatforms()
    {
        $platforms = $this->platformService
            ->listUserPlatforms(auth('api')->user()->id);

        return Response::api(
            __('message.Platforms retrieved successfully'),
            200,
            true,
            null,
            ['platforms' => $platforms]
        );
    }
    public function toggle(int $platformId)
    {
        $state = $this->platformService
            ->togglePlatform(auth('api')->user()->id, $platformId);

        return Response::api(
            $state ? __('message.Platform activated for the user')
                : __('message.Platform deactivated for the user'),
            200,
            true,
            null,
        );
    }
}
