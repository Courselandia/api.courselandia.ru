<?php
/**
 * Основные провайдеры.
 *
 * @package App.Providers
 */

namespace App\Providers;

use App;
use Sms;
use Illuminate\Support\ServiceProvider;
use App\Models\Sms\SmsManager;
use App\Models\Sms\SmsCenter;
use App\Models\Sms\SmsLog;

/**
 * Класс сервис-провайдера для смс.
 */
class SmsServiceProvider extends ServiceProvider
{
    /**
     * Регистрация сервис провайдеров.
     *
     * @return void
     */
    public function register(): void
    {
        App::singleton('sms', function ($app) {
            return new SmsManager($app);
        });

        Sms::extend('center', function () {
            return new SmsCenter();
        });

        Sms::extend('log', function () {
            return new SmsLog();
        });
    }
}
