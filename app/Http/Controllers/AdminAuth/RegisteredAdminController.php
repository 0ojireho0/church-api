<?php

namespace App\Http\Controllers\AdminAuth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredAdminController extends Controller
{
    //

    public function store(Request $request){

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Admin::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'username' => ['required', 'unique:'.Admin::class]
        ]);

        $user = Admin::create([
            'fullname' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
            'username' => $request->username,
            'church_id' => $request->church_id
        ]);

        // event(new Registered($user));

        // Auth::login($user);

        return response()->noContent();
    }
}
