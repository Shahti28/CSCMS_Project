<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ActivityLog;

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

        // Role-based credentials (Feature 20)
        $validUsers = [
            'admin'      => ['password' => '12345', 'role' => 'admin'],
            'librarian'  => ['password' => '12345', 'role' => 'librarian'],
            'accountant' => ['password' => '12345', 'role' => 'accountant'],
        ];

        if (isset($validUsers[$username]) && $validUsers[$username]['password'] === $password) {
            session([
                'user' => $username,
                'role' => $validUsers[$username]['role']
            ]);

            // Log the login activity (Feature 19)
            ActivityLog::create([
                'user' => $username,
                'action' => 'login',
                'module' => 'Authentication',
                'description' => "User '{$username}' logged in successfully",
                'ip_address' => $request->ip()
            ]);

            return redirect('/dashboard');
        }

        return redirect('/login')->with('error', 'Invalid credentials');
    }

    public function logout(Request $request)
    {
        $username = session('user', 'Unknown');

        // Log the logout activity (Feature 19)
        ActivityLog::create([
            'user' => $username,
            'action' => 'logout',
            'module' => 'Authentication',
            'description' => "User '{$username}' logged out",
            'ip_address' => $request->ip()
        ]);

        session()->forget(['user', 'role']);
        return redirect('/login');
    }
}
