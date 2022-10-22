<?php
/**
 * Модуль предупреждений.
 * Этот модуль содержит все классы для работы с предупреждениями.
 *
 * @package App\Modules\Alert
 */

namespace App\Modules\Alert\Tests\Browser\Admin;

use Alert;
use App\Models\Test\HelpAdminBrowser;
use Faker\Factory as Faker;
use Throwable;
use Tests\DuskTestCase;

/**
 * Тестирование UI: Класс контроллер для тестирования предупреждений.
 */
class AlertBrowser extends DuskTestCase
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
            $faker = Faker::create();
            $name = $faker->name;
            Alert::add($name);

            $this->getLogin($browser, 'Alert', 'index')
                ->screenshot('Alert_admin_index_step_6')
                ->visit('/dashboard/alerts')
                ->pause(1000 * 30)
                ->screenshot('Alert_admin_index_step_7')
                ->assertPresent('.v-data-table TABLE TBODY TR TD.text-start')
                ->type('INPUT[name=search]', $name)
                ->pause(1000 * 30)
                ->screenshot('Alert_admin_index_step_8')
                ->assertPresent('.v-data-table TABLE TBODY TR TD.text-start')
                ->click('@deactivated')
                ->pause(1000 * 30)
                ->screenshot('Alert_admin_index_step_9')
                ->assertPresent('@active')
                ->click('@active')
                ->pause(1000 * 30)
                ->screenshot('Alert_admin_index_step_10')
                ->assertPresent('@deactivated');
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
            $faker = Faker::create();
            $name = $faker->name;
            Alert::add($name);

            $test = $this->getLogin($browser, 'Alert', 'destroy')
                ->screenshot('Alert_admin_destroy_step_6')
                ->visit('/dashboard/alerts')
                ->pause(1000 * 30)
                ->screenshot('Alert_admin_destroy_step_7');

            $this->clear($browser, 'INPUT[name=search]');

            $test->pause(1000 * 30)
                ->screenshot('Alert_admin_destroy_step_8')
                ->click('@delete')
                ->pause(1000 * 30)
                ->screenshot('Alert_admin_destroy_step_9')
                ->pause(1000)
                ->click('@dialog-agree')
                ->pause(1000 * 20)
                ->screenshot('Alert_admin_destroy_step_10')
                ->assertNotPresent('.v-snack.v-snack--active');
        });
    }
}
