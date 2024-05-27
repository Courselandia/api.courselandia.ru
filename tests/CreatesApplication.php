<?php

namespace Tests;

use Artisan;
use Cache;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
    /**
     * Создание приложения.
     *
     * @return Application
     */
    public function createApplication(): Application
    {
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();

        Cache::flush();
        Artisan::call('config:clear');
        Artisan::call('view:clear');
        Artisan::call('config:cache');

        return $app;
    }
}
