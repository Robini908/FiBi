<?php

namespace App\Http\Middleware\Custom;

use Closure;
use App\Helpers\Qs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class TeamAdministratorAccount
{
    /**
     * Handle an incoming request.
     * Allow access for administrators, admins, and accountants
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return (Auth::check() && (Qs::isAdministrator() || Qs::isAdmin() || Qs::isAccountant())) 
                ? $next($request) 
                : redirect()->route('login');
    }
} 