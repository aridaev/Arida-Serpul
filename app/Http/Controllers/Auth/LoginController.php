<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['username' => 'Username atau password salah.']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|max:50|unique:users',
            'password' => 'required|string|min:6|confirmed',
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
            'currency_id' => 1, // Default IDR
            'level' => 'member',
            'referral_code' => strtoupper(Str::random(8)),
            'referred_by' => $referrer?->id,
            'komisi_referral' => 5, // Default 5%
            'status' => true,
        ]);

        Auth::login($user);
        return redirect('/dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
