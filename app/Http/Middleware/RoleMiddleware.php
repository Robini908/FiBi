<?php

namespace App\Http\Middleware;

use App\Helpers\Qs;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     * This middleware checks if the authenticated user has at least one of the specified roles.
     * Multiple roles can be provided as comma-separated values.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $roles  Comma-separated list of allowed roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        // No access if not authenticated
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        // Parse roles from comma-separated string
        $allowedRoles = array_map('trim', explode(',', $roles));
        
        // Map of common role name variations
        $roleMap = [
            'superadmin' => 'super_admin',
            'librarian' => 'librarian',
            'admin' => 'admin',
            'student' => 'student',
            'parent' => 'parent',
            'teacher' => 'teacher',
            'accountant' => 'accountant',
        ];
        
        // Check if user has any of the allowed roles
        foreach ($allowedRoles as $role) {
            // Normalize role name to lowercase
            $roleLower = strtolower($role);
            
            // Get the standard role name if it exists in our map
            $standardRole = $roleMap[$roleLower] ?? $role;
            
            // Create method name for the Qs helper class
            $checkMethod = 'is' . ucfirst($standardRole);
            
            // Check if the method exists in Qs helper and if user has the role
            if (method_exists(Qs::class, $checkMethod) && Qs::$checkMethod()) {
                return $next($request);
            }
            
            // Also check legacy method names (backward compatibility)
            $legacyMethod = 'userIs' . ucfirst($standardRole);
            if (method_exists(Qs::class, $legacyMethod) && Qs::$legacyMethod()) {
                return $next($request);
            }
            
            // Special case handlers
            if ($roleLower === 'super_admin' && method_exists(Qs::class, 'isSuperAdmin') && Qs::isSuperAdmin()) {
                return $next($request);
            }
            
            if ($roleLower === 'superadmin' && method_exists(Qs::class, 'isSuperAdmin') && Qs::isSuperAdmin()) {
                return $next($request);
            }
        }
        
        // If we get here, the user doesn't have any of the required roles
        abort(403, 'Unauthorized action. You do not have the required permissions.');
    }
} 