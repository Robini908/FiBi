<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Qs;
use Illuminate\Http\Request;

class LibrarianOnly
{
    /**
     * Handle an incoming request.
     * Ensures that the user is authenticated and has ONLY the librarian role.
     * Administrators with multiple roles are excluded.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if user is authenticated and has ONLY the librarian role
        if (Auth::check() && Qs::isLibrarian()) {
            $user = Auth::user();
            // If the user has exactly one role and it's librarian
            if ($user->roles->count() === 1 && $user->roles->first()->name === 'librarian') {
                return $next($request);
            }
        }
        
        // Redirect to login if not a librarian-only user
        return redirect()->route('login');
    }
} 