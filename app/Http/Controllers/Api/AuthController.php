<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed',
            'phone_number' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone_number' => $data['phone_number'],
        ]);

        $token = $user->createToken('web')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'user'    => $user,
            'token'   => $token, // optional (untuk API token-style)
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['Email atau password salah.'],
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();
        $token = $user->createToken('web')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil.',
            'user' => $user,
            'token' => $token,
        ]);
    }



    public function logout(Request $request)
    {
        // Jika pakai token
        if ($request->user()?->currentAccessToken()) {
            $request->user()->currentAccessToken()->delete();
        }

        // Jika pakai session
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logout berhasil.']);
    }


}
