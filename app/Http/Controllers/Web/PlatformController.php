<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Platform;
use App\Services\Contracts\PlatformServiceInterface;
use Illuminate\Http\Request;

class PlatformController extends Controller
{
    public $platformService;
    public function __construct(PlatformServiceInterface $platformService)
    {
        $this->platformService = $platformService;
    }
    public function index()
    {
        $user = auth('web')->user();

        $userPlatforms = $this->platformService->listUserPlatforms($user->id);

        $availablePlatforms = $this->platformService->getplatformsNotInUserPlatforms($user->id, $userPlatforms->pluck('id')->toArray());

        return view('platforms.index', compact('userPlatforms', 'availablePlatforms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'platform_id' => 'required|exists:platforms,id'
        ]);

        $user = auth('web')->user();
        $this->platformService->togglePlatform($user->id, $validated['platform_id']);

        return redirect()->route('web.platforms.index')
            ->with('success', __('message.Platform connected successfully'));
    }

    public function destroy(int $id)
    {
        $user = auth('web')->user();
        $this->platformService->togglePlatform($user->id, $id);

        return redirect()->route('web.platforms.index')
            ->with('success', __('message.Platform disconnected successfully'));
    }
}
