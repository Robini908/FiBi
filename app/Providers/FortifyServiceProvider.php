<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use App\User;
use Illuminate\Support\Facades\Log;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $identity = (string) $request->identity;

            return Limit::perMinute(5)->by($identity.$request->ip());
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Custom authentication logic for username or email login with robust error handling
        Fortify::authenticateUsing(function (Request $request) {
            try {
                // Validate required fields
                if (empty($request->identity) || empty($request->password)) {
                    return null;
                }
                
                // Find the user by email or username
                $user = User::where(function ($query) use ($request) {
                    $query->where('email', $request->identity)
                          ->orWhere('username', $request->identity);
                })->first();
                
                // If user exists and password is correct
                if ($user && Hash::check($request->password, $user->password)) {
                    // Log successful authentication
                    Log::info('User authenticated successfully', [
                        'user_id' => $user->id, 
                        'email' => $user->email,
                        'ip' => $request->ip()
                    ]);
                    
                    // Use our custom session service if available
                    if (class_exists('\App\Services\SessionGuardService')) {
                        $remember = $request->boolean('remember');
                        \App\Services\SessionGuardService::startSession($user, $remember);
                    }
                    
                    return $user;
                }
                
                // If authentication failed, log the attempt
                if ($user) {
                    Log::warning('Failed login attempt - incorrect password', [
                        'user_id' => $user->id,
                        'email' => $user->email,
                        'ip' => $request->ip()
                    ]);
                } else {
                    Log::warning('Failed login attempt - user not found', [
                        'identity' => $request->identity,
                        'ip' => $request->ip()
                    ]);
                }
                
                return null;
            } catch (\Exception $e) {
                // Log any errors that occur during authentication
                Log::error('Authentication error in Fortify authenticateUsing', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'identity' => $request->identity ?? 'not provided',
                    'ip' => $request->ip()
                ]);
                
                return null;
            }
        });
        
        // Custom views for authentication
        Fortify::loginView(function () {
            return view('auth.login');
        });
        
        Fortify::registerView(function () {
            return view('auth.register');
        });
        
        Fortify::requestPasswordResetLinkView(function () {
            return view('auth.forgot-password');
        });
        
        Fortify::resetPasswordView(function ($request) {
            return view('auth.reset-password', ['request' => $request]);
        });
        
        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });
        
        Fortify::confirmPasswordView(function () {
            return view('auth.confirm-password');
        });
    }
}
