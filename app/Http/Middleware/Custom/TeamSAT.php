<?php

namespace App\Http\Middleware\Custom;

use Closure;
use App\Helpers\Qs;
use Illuminate\Support\Facades\Auth;

class TeamSAT
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Using the new function name isAdministratorOrTeacher() which replaces userIsTeamSAT()
        return (Auth::check() && Qs::isAdministratorOrTeacher()) ? $next($request) : redirect()->route('login');
    }
}
