<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Notifications\CustomResetPassword;

class PasswordResetLinkController extends Controller
{
    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            throw ValidationException::withMessages([
                'email' => [__($status)],
            ]);
        }

        return response()->json(['status' => __($status)]);
    }

    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email', // adjust table if needed
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid email',
                'errors' => $validator->errors()
            ], 422);
        }

        // Get the user
        $user = User::where('email', $request->email)->first();

        // Generate token
        $token = Password::createToken($user);

        $notification = new CustomResetPassword($token);
        $notification->sendCustomEmail($user);

        return response()->json([
            'message' => 'Password reset link sent!'
        ]);
    }
}
