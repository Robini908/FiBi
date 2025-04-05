<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Qs;
use Illuminate\Http\Request;

class Librarian
{
    /**
     * Handle an incoming request.
     * Ensures that the user is authenticated and has a librarian role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return (Auth::check() && Qs::isLibrarian()) ? $next($request) : redirect()->route('login');
    }
} 