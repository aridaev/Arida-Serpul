<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthApiController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Username atau password salah.'], 401);
        }

        if (!$user->status) {
            return response()->json(['message' => 'Akun dinonaktifkan.'], 403);
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'nama' => $user->nama,
                'email' => $user->email,
                'no_hp' => $user->no_hp,
                'saldo' => $user->saldo,
                'level' => $user->level,
                'referral_code' => $user->referral_code,
                'currency' => $user->currency->code,
            ],
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|max:50|unique:users',
            'password' => 'required|string|min:6',
            'nama' => 'required|string|max:100',
            'no_hp' => 'required|string|max:20',
            'email' => 'required|email|max:100|unique:users',
            'referral_code' => 'nullable|string|exists:users,referral_code',
        ]);

        $referrer = null;
        if ($request->referral_code) {
            $referrer = User::where('referral_code', $request->referral_code)->first();
        }

        $user = User::create([
            'username' => $request->username,
            'password' => $request->password,
            'nama' => $request->nama,
            'no_hp' => $request->no_hp,
            'email' => $request->email,
            'saldo' => 0,
            'currency_id' => 1,
            'level' => 'member',
            'referral_code' => strtoupper(Str::random(8)),
            'referred_by' => $referrer?->id,
            'komisi_referral' => 5,
            'status' => true,
        ]);

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'message' => 'Registrasi berhasil',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'nama' => $user->nama,
                'saldo' => $user->saldo,
                'referral_code' => $user->referral_code,
            ],
        ], 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    }

    public function profile(Request $request)
    {
        $user = $request->user()->load('currency');
        return response()->json([
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'nama' => $user->nama,
                'email' => $user->email,
                'no_hp' => $user->no_hp,
                'saldo' => $user->saldo,
                'level' => $user->level,
                'referral_code' => $user->referral_code,
                'currency' => $user->currency,
            ],
        ]);
    }
}
