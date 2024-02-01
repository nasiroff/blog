<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\VerificationEmailRequest;
use Illuminate\Auth\Events\Verified;

class EmailVerifyController extends Controller
{
    public function __invoke(VerificationEmailRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route(SITE_HOME);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->intended(SITE_HOME);
    }
}
