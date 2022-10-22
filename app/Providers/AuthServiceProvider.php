<?php

namespace App\Providers;

use Auth;
use App\Modules\Access\Models\AccessUserProvider;
use App\Modules\User\Repositories\User;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @param  GateContract  $gate
     * @return void
     */
    public function boot(GateContract $gate): void
    {
        $this->registerPolicies();

        Auth::provider('access', function($app) {
            return new AccessUserProvider($app->make(User::class));
        });

        $gate->define('role', 'App\Modules\Access\Models\GateRole@check');
        $gate->define('user', 'App\Modules\Access\Models\GateUser@check');
        $gate->define('verified', 'App\Modules\Access\Models\GateVerified@check');
    }
}
