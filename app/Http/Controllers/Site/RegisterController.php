<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
{
    public function page()
    {
        return view('register');
    }

    public function register(RegisterRequest $request)
    {
        $payload = array_merge($request->validated(), [
            'token' => md5(uniqid())
        ]);
        $user    = User::query()->create($payload);
        event(new Registered($user));
        return redirect()->route(SITE_HOME);
    }
}
