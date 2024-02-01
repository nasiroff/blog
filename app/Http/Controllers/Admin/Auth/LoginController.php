<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function page(): View
    {
        return \view('admin.auth.login');
    }


    public function login(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'username'   => 'required',
            'password' => 'required|min:6'
        ]);

        if (adminGuard()->attempt([
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ])) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        return \redirect()->back()->withErrors(['error' => 'The provided credentials do not match our records.']);
    }

}



