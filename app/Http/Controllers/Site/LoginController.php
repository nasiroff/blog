<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function index(): View
    {
        return \view('login');
    }


    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        if (Auth::guard()->attempt($credentials)) {
            $token = md5(uniqid());
            \auth()->user()->update(['token' => $token]);
            $request->session()->regenerate();
            return redirect()->route('site.home');
        }

        return \redirect()->back()->withErrors(['error' => 'The provided credentials do not match our records.']);
    }

}
