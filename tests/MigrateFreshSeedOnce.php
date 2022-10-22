<?php

namespace Tests;

use Cache;
use Illuminate\Support\Facades\Artisan;

trait MigrateFreshSeedOnce
{
    /**
     * If true, setup has run at least once.
     *
     * @var bool
     */
    protected static bool $setUpHasRunOnce = false;

    /**
     * After the first run of setUp "migrate:fresh --seed"
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        if (!static::$setUpHasRunOnce) {
            ini_set('memory_limit', '1000M');
            ini_set('max_execution_time', 60 * 60 * 24 * 30);
            ini_set('max_input_time', 600);

            Cache::flush();
            Artisan::call('view:clear');
            Artisan::call('config:cache');

            Artisan::call('migrate:fresh');
            Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);

            static::$setUpHasRunOnce = true;
        }
    }
}
