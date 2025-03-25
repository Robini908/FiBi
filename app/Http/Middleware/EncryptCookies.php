<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;
use Illuminate\Contracts\Encryption\Encrypter;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
        'XSRF-TOKEN', // CSRF token doesn't need encryption
        'remember_web_*', // Remember tokens are already hashed
        'theme_preference', // UI preferences don't need encryption
        'sidebar_collapsed', // UI preferences don't need encryption
    ];
    
    /**
     * Constructor to set up additional encrypted cookies.
     *
     * @param  \Illuminate\Contracts\Encryption\Encrypter  $encrypter
     * @return void
     */
    public function __construct(Encrypter $encrypter)
    {
        parent::__construct($encrypter);
        
        // Add more cookies to be excluded if needed
    }
}
