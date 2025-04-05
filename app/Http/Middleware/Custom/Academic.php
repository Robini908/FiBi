<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Qs;
use Illuminate\Http\Request;

class Academic
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Exclude librarians from academic middleware
        return (Auth::check() && Qs::isAcademicStaff() && !Qs::isLibrarian()) ? $next($request) : redirect()->route('login');
    }
} 