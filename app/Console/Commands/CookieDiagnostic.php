<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Cookie\CookieJar;

class CookieDiagnostic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'diagnose:cookies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnose cookie and session configuration issues';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('======== Cookie Jar Diagnostic ========');
        
        // Check if cookie jar is bound
        $this->info('Checking if cookie jar is bound...');
        if (App::bound('cookie')) {
            $this->info('✅ Cookie jar is bound to container');
            
            // Try to resolve it
            try {
                $cookieJar = App::make('cookie');
                if ($cookieJar instanceof CookieJar) {
                    $this->info('✅ Cookie jar resolves to correct class: ' . get_class($cookieJar));
                } else {
                    $this->error('❌ Cookie jar resolves to incorrect class: ' . (is_object($cookieJar) ? get_class($cookieJar) : gettype($cookieJar)));
                }
            } catch (\Exception $e) {
                $this->error('❌ Error resolving cookie jar: ' . $e->getMessage());
            }
        } else {
            $this->error('❌ Cookie jar is NOT bound to container!');
        }
        
        // Check session configuration
        $this->info("\nChecking session configuration...");
        $this->table(
            ['Setting', 'Value'],
            [
                ['driver', config('session.driver')],
                ['lifetime', config('session.lifetime')],
                ['expire_on_close', config('session.expire_on_close') ? 'Yes' : 'No'],
                ['encrypt', config('session.encrypt') ? 'Yes' : 'No'],
                ['cookie', config('session.cookie')],
                ['path', config('session.path')],
                ['domain', config('session.domain') ?: 'NULL'],
                ['secure', config('session.secure') ? 'Yes' : 'No'],
                ['http_only', config('session.http_only') ? 'Yes' : 'No'],
                ['same_site', config('session.same_site')],
            ]
        );
        
        // Check Sanctum configuration if available
        $this->info("\nChecking Sanctum configuration...");
        if (config()->has('sanctum')) {
            $this->table(
                ['Setting', 'Value'],
                [
                    ['cookie.name', config('sanctum.cookie.name')],
                    ['cookie.domain', config('sanctum.cookie.domain') ?: 'NULL'],
                    ['cookie.path', config('sanctum.cookie.path')],
                    ['cookie.secure', config('sanctum.cookie.secure') ? 'Yes' : 'No'],
                    ['cookie.same_site', config('sanctum.cookie.same_site')],
                ]
            );
        } else {
            $this->warn('Sanctum configuration not found');
        }
        
        // Attempt to create a test cookie
        $this->info("\nTesting cookie creation...");
        try {
            $cookieJar = new CookieJar();
            $cookie = $cookieJar->make('test_cookie', 'test_value', 60, '/', null, false, true, false, 'lax');
            $this->info('✅ Test cookie created successfully');
            $this->info("Cookie name: {$cookie->getName()}");
            $this->info("Cookie value: {$cookie->getValue()}");
        } catch (\Exception $e) {
            $this->error('❌ Failed to create test cookie: ' . $e->getMessage());
        }
        
        $this->info("\n======== Diagnostic Complete ========");
        
        return Command::SUCCESS;
    }
} 