<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthAdmin\LoginRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    //

    public function store(LoginRequest $request)
    {
        $request->authenticate(['guard' => 'admin']);

        $request->session()->regenerate();

        $redirectTo = "/dashboard";

        return response()->json([
            'message' => 'Login Successfull',
            'redirect_to' => $redirectTo
        ], 200);
    }
}
