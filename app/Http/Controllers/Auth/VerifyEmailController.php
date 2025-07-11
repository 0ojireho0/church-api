<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use App\Models\User;

class VerifyEmailController
{
    public function __invoke(Request $request)
    {
        $user = User::findOrFail($request->route('id'));

        if (! URL::hasValidSignature($request)) {
            abort(403, 'Invalid or expired verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('http://localhost:3000/login?verified=1');
        }

        $user->markEmailAsVerified();

        return redirect('http://localhost:3000/login?verified=success');
    }
}

