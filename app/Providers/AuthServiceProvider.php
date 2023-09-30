<?php

namespace App\Providers;

use App\Guards\TokenGuard;
use App\Models\UserToken;
use App\Policies\UserTokenPolicy;
use App\Services\AuthService;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        UserToken::class => UserTokenPolicy::class
    ];

    /**
     * RegisterDTO any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Auth::extend('token', function ($app, $name, array $config) {
            return new TokenGuard($app['request'],(new AuthService()));
        });
    }
}
