<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:users,id',
            'name' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:users,username,' . $request->id,
            'email' => 'nullable|email|unique:users,email,' . $request->id,
            'password' => 'nullable|string|min:6',
            'contact' => 'nullable|string|max:20',
        ]);

        $user = User::findOrFail($validated['id']);

        $user->name = $validated['name'];

        if (!empty($validated['username'])) {
            $user->username = $validated['username'];
        }

        if (!empty($validated['email'])) {
            $user->email = $validated['email'];
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if (!empty($validated['contact'])) {
            $user->contact = $validated['contact'];
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'requireReLogin' => !empty($validated['password']),
        ], 200);
    }

    public function updateAdmin(Request $request){
        $validated = $request->validate([
            'id' => 'required|exists:admins,id',
            'fullname' => 'required|string|max:255',
            'username' => 'nullable|string|max:255|unique:admins,username,' . $request->id,
            'email' => 'nullable|email|unique:admins,email,' . $request->id,
            'password' => 'nullable|string|min:6',
        ]);

        $user = Admin::findOrFail($validated['id']);

        $user->fullname = $validated['fullname'];

        if (!empty($validated['username'])) {
            $user->username = $validated['username'];
        }

        if (!empty($validated['email'])) {
            $user->email = $validated['email'];
        }

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
        ], 200);
    }


}
