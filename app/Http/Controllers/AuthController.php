<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if (($username === 'admin' && $password === '12345') || ($username === 'librarian' && $password === '12345')) {
            session(['user' => $username]);
            return redirect('/dashboard'); // redirect to main dashboard after login
        }

        return redirect('/login')->with('error', 'Invalid credentials');
    }

    public function logout()
    {
        session()->forget('user');
        return redirect('/login');
    }
}
