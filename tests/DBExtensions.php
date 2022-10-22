<?php

namespace Tests;

use Artisan;
use Cache;
use PHPUnit\Runner\AfterLastTestHook;

class DBExtensions implements AfterLastTestHook
{
    public function executeAfterLastTest(): void
    {
        Cache::flush();
        //Artisan::call('view:clear');
        //Artisan::call('config:cache');
    }
}
