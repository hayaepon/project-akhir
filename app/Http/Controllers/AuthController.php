<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if ($user->role == 'super_admin') {
                return redirect()->route('superadmin.dashboard');
            } else {
                return redirect()->route('admin.dashboard');
            }
        }

        return back()->withErrors(['email' => 'Login gagal.']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
