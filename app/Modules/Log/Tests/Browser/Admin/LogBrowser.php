<?php
/**
 * Модуль Логирование.
 * Этот модуль содержит все классы для работы с логированием.
 *
 * @package App\Modules\Log
 */

namespace App\Modules\Log\Tests\Browser\Admin;

use App\Models\Test\HelpAdminBrowser;
use Throwable;
use Tests\DuskTestCase;
use Log;

/**
 * Тестирование UI: Класс контроллер для тестирования логов.
 */
class LogBrowser extends DuskTestCase
{
    use HelpAdminBrowser;

    /**
     * Определяем основной URL.
     *
     * @return string
     */
    protected function baseUrl(): string
    {
        return rtrim(config('app.admin_url'), '/');
    }

    /**
     * Проверка главной страницы раздела.
     *
     * @return void
     * @throws Throwable
     */
    public function testIndex()
    {
        $this->browse(function ($browser) {
            $text = 'Test warning.';
            Log::warning($text);

            $this->getLogin($browser, 'Log', 'index')
                ->screenshot('Log_admin_index_step_6')
                ->visit('/dashboard/logs')
                ->pause(1000 * 30)
                ->screenshot('Log_admin_index_step_7')
                ->assertPresent('.v-data-table TABLE TBODY TR TD.text-start')
                ->type('INPUT[name=search]', $text)
                ->pause(1000 * 30)
                ->screenshot('Log_admin_index_step_8')
                ->assertPresent('.v-data-table TABLE TBODY TR TD.text-start');
        });
    }

    /**
     * Проверка обновления.
     *
     * @return void
     * @throws Throwable
     */
    public function testUpdate()
    {
        $this->browse(function ($browser) {
            $text = 'Test warning.';
            Log::warning($text);

            $this->getLogin($browser, 'Log', 'update')
                ->screenshot('Log_admin_update_step_6')
                ->visit('/dashboard/logs')
                ->pause(1000 * 30)
                ->screenshot('Log_admin_update_step_7')
                ->click('@edit')
                ->pause(1000 * 30)
                ->screenshot('Log_admin_update_step_8')
                ->assertPresent('.v-data-table TABLE TBODY TR TD.log.name');
        });
    }

    /**
     * Проверка удаления.
     *
     * @return void
     * @throws Throwable
     */
    public function testDestroy()
    {
        $this->browse(function ($browser) {
            $text = 'Test warning.';
            Log::warning($text);

            $test = $this->getLogin($browser, 'Log', 'destroy')
                ->screenshot('Log_admin_destroy_step_6')
                ->visit('/dashboard/logs')
                ->pause(1000 * 30)
                ->screenshot('Log_admin_destroy_step_7');

            $this->clear($browser, 'INPUT[name=search]');

            $test->pause(1000 * 30)
                ->screenshot('Log_admin_destroy_step_8')
                ->click('@delete')
                ->pause(1000 * 30)
                ->screenshot('Log_admin_destroy_step_9')
                ->pause(1000)
                ->click('@dialog-agree')
                ->pause(1000 * 20)
                ->screenshot('Log_admin_destroy_step_10')
                ->assertNotPresent('.v-snack.v-snack--active');
        });
    }
}
