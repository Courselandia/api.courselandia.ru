<?php

namespace App\Providers;

use App;
use App\Models\Bot;
use App\Models\Device;
use App\Models\Util;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        if ($this->app->environment('local')) {
            $this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            $this->app->register(TelescopeServiceProvider::class);
        }

        App::bind(
            'util',
            function () {
                return new Util();
            }
        );

        App::bind(
            'device',
            function () {
                return new Device();
            }
        );

        App::bind(
            'bot',
            function () {
                return new Bot();
            }
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
