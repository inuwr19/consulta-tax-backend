<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'  => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated',
            'user'    => $user,
        ]);
    }
}

