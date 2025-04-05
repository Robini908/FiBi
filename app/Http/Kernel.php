<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\DiagnosticMiddleware::class,
        \App\Http\Middleware\EnsureCookieJarExists::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EnsureCookieJarExists::class,
            \App\Http\Middleware\EncryptCookies::class,
            \App\Http\Middleware\EnsureAuthWorks::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \App\Http\Middleware\SessionManager::class,
            \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\VerifyAndRefreshSession::class,
            \App\Http\Middleware\FixCookieJarMiddleware::class,
            \App\Http\Middleware\TrackUserActivity::class,
        ],

        'api' => [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
        'tenant' => [
            \App\Http\Middleware\InitializeTenancy::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'admin' => \App\Http\Middleware\Custom\Admin::class,
        'super_admin' => \App\Http\Middleware\Custom\SuperAdmin::class,
        
        // Role-based middleware
        'role' => \App\Http\Middleware\RoleMiddleware::class,
        
        // Role-based middleware (new descriptive names)
        'administrator' => \App\Http\Middleware\Custom\TeamSA::class,
        'administrator_teacher' => \App\Http\Middleware\Custom\TeamSAT::class,
        'administrator_accountant' => \App\Http\Middleware\Custom\TeamAdministratorAccount::class,
        'accountant' => \App\Http\Middleware\Custom\TeamAccount::class,
        'teacher' => \App\Http\Middleware\Custom\Teacher::class,
        'librarian' => \App\Http\Middleware\Custom\Librarian::class,
        'librarian_only' => \App\Http\Middleware\Custom\LibrarianOnly::class,
        'student' => \App\Http\Middleware\Custom\Student::class,
        'parent' => \App\Http\Middleware\Custom\MyParent::class,
        'academic' => \App\Http\Middleware\Custom\Academic::class,
        'pta_member' => \App\Http\Middleware\Custom\PtaMember::class,
        
        // Keep old middleware names for backward compatibility
        'teamSA' => \App\Http\Middleware\Custom\TeamSA::class,
        'teamSAT' => \App\Http\Middleware\Custom\TeamSAT::class,
        'teamAccount' => \App\Http\Middleware\Custom\TeamAccount::class,
        'my_parent' => \App\Http\Middleware\Custom\MyParent::class,
        
        'examIsLocked' => \App\Http\Middleware\Custom\ExamIsLocked::class,
    ];

    protected $middlewareAliases = [
        // New middleware not already defined in routeMiddleware
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'checkUserType' => \App\Http\Middleware\CheckUserType::class,
    ];
}
