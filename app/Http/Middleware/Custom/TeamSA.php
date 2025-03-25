<?php

namespace App\Http\Middleware\Custom;

use Closure;
use App\Helpers\Qs;
use Illuminate\Support\Facades\Auth;

class TeamSA
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
        // Using the new function name isAdministrator() which replaces userIsTeamSA()
        return (Auth::check() && Qs::isAdministrator()) ? $next($request) : redirect()->route('login');
    }
}
