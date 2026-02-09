<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('admin', fn ($user) => $user->role === 'admin');
        Gate::define('user',  fn ($user) => $user->role === 'user');
    }
}
