<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Services\Contracts\AuthServiceInterface;

class AuthController extends Controller
{
    public $authService;
    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }
    public function loginView()
    {
        return view('auth.login');
    }
    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->email, $request->password);
        if (!$result['success'])
            return back()->with('error', $result['message']);

        auth('web')->login($result['user']);
        return redirect()->route('web.dashboard')->with('success', __('message.Login Successfully'));
    }

    public function registerView()
    {
        return view('auth.register');
    }
    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password
        ]);

        if (!$result['success'])
            return back()->with('error', $result['message']);

        auth('web')->login($result['user']);
        return redirect()->route('web.dashboard')->with('success', __('message.Login Successfully'));
    }
    public function logout()
    {
        auth('web')->logout();
        return redirect()->route('web.view.login');
    }

    public function getProfile()
    {
        $user = auth('web')->user();
        return view('auth.profile', compact('user'));
    }
    public function updateProfile(UpdateProfileRequest $request)
    {
        $result = $this->authService
            ->updateProfile(auth('web')->user()->id, [
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => $request->password
            ]);
        if (!$result['success'])
            return back()->with('error', $result['message']);

        return redirect()->route('web.profile.get')->with('success', __('message.Profile Updated Successfully'));
    }
}
