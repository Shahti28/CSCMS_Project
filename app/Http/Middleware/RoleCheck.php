<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleCheck
{
    /**
     * Handle role-based access control (Feature 20).
     * Restricts access to system modules based on user role.
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $userRole = $request->session()->get('role', '');

        if (!in_array($userRole, $roles)) {
            return redirect('/dashboard')->with('error', 'Access denied. You do not have permission to access this module.');
        }

        return $next($request);
    }
}
