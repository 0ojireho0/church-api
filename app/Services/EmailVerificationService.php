<?php

namespace App\Services;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use App\Models\User;

class EmailVerificationService
{
    public function sendVerificationEmail(User $user): void
    {
        if ($user->hasVerifiedEmail()) {
            return;
        }

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $body = view('verify-email', [
            'user' => $user,
            'url' => $verificationUrl
        ])->render();

        (new SendingEmail(
            email: $user->email,
            body: $body,
            subject: 'Verify Your Email Address'
        ))->send();
    }
}
