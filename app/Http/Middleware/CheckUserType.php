<?php

namespace App\Http\Middleware;

use App\Helpers\Qs;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user has any of the allowed roles
        $hasRole = false;
        
        foreach ($roles as $role) {
            if ($role === 'admin' && Qs::isAdmin()) {
                $hasRole = true;
                break;
            }
            if ($role === 'super-admin' && Qs::isAdministrator()) {
                $hasRole = true;
                break;
            }
            if ($role === 'teacher' && Qs::isTeacher()) {
                $hasRole = true;
                break;
            }
            if ($role === 'accountant' && Qs::isAccountant()) {
                $hasRole = true;
                break;
            }
            if ($role === 'parent' && Qs::isParent()) {
                $hasRole = true;
                break;
            }
            if ($role === 'student' && Qs::isStudent()) {
                $hasRole = true;
                break;
            }
        }
        
        if (!$hasRole) {
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
        }
        
        return $next($request);
    }
}
