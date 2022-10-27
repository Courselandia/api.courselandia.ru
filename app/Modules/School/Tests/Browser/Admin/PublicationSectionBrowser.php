<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Tests\Browser\Admin;

use App\Models\Test\HelpAdminBrowser;
use App\Modules\School\Models\School;
use App\Modules\School\Models\SchoolSection;
use Faker\Factory as Faker;
use Throwable;
use Tests\DuskTestCase;

/**
 * Тестирование UI: Класс контроллер для тестирование разделов школ.
 */
class SchoolSectionBrowser extends DuskTestCase
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
            $schoolSection = SchoolSection::factory()->create();

            $this->getLogin($browser, 'SchoolSection', 'index')
                ->screenshot('SchoolSection_index_step_6')
                ->visit('/dashboard/schools/sections')
                ->pause(1000 * 30)
                ->screenshot('SchoolSection_index_step_7')
                ->assertPresent('.v-data-table TABLE TBODY TR TD.text-start')
                ->type('INPUT[name=search]', $schoolSection["name"])
                ->pause(1000 * 30)
                ->screenshot('SchoolSection_index_step_8')
                ->assertPresent('.v-data-table TABLE TBODY TR TD.text-start')
                ->click('@active')
                ->pause(1000)
                ->click('@dialog-agree')
                ->pause(1000 * 30)
                ->screenshot('SchoolSection_index_step_9')
                ->assertPresent('@deactivated')
                ->click('@deactivated')
                ->pause(1000)
                ->click('@dialog-agree')
                ->pause(1000 * 30)
                ->screenshot('SchoolSection_index_step_10')
                ->assertPresent('@active');
        });
    }

    /**
     * Проверка создания.
     *
     * @return void
     * @throws Throwable
     */
    public function testCreate()
    {
        $this->browse(function ($browser) {
            $faker = Faker::create();

            $this->getLogin($browser, 'SchoolSection', 'create')
                ->screenshot('SchoolSection_create_step_6')
                ->visit('/dashboard/schools/sections')
                ->pause(1000 * 30)
                ->screenshot('SchoolSection_create_step_7')
                ->click('@add')
                ->pause(1000 * 30)
                ->screenshot('SchoolSection_create_step_8')
                ->type('INPUT[name=name]', $faker->name)
                ->screenshot('SchoolSection_create_step_9')
                ->click('@send')
                ->screenshot('SchoolSection_create_step_10')
                ->pause(1000 * 30)
                ->screenshot('SchoolSection_create_step_11')
                ->assertPresent('.v-alert.success');
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
            $faker = Faker::create();
            SchoolSection::factory()->create();

            $this->getLogin($browser, 'SchoolSection', 'update')
                ->screenshot('SchoolSection_update_step_6')
                ->visit('/dashboard/schools/sections')
                ->pause(1000 * 30)
                ->screenshot('SchoolSection_update_step_7')
                ->click('@edit')
                ->pause(1000 * 30)
                ->screenshot('SchoolSection_update_step_8')
                ->click('@reset')
                ->screenshot('SchoolSection_update_step_9')
                ->type('INPUT[name=name]', $faker->name)
                ->screenshot('SchoolSection_update_step_10')
                ->click('@send')
                ->screenshot('SchoolSection_update_step_11')
                ->pause(1000 * 30)
                ->screenshot('SchoolSection_update_step_12')
                ->assertPresent('.v-alert.success');
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
            School::factory()->create();

            $test = $this->getLogin($browser, 'SchoolSection', 'destroy')
                ->screenshot('SchoolSection_destroy_step_6')
                ->visit('/dashboard/schools/sections')
                ->pause(1000 * 30)
                ->screenshot('SchoolSection_destroy_step_7');

            $this->clear($browser, 'INPUT[name=search]');

            $test->pause(1000 * 30)
                ->screenshot('SchoolSection_destroy_step_8')
                ->click('@delete')
                ->pause(1000 * 30)
                ->screenshot('SchoolSection_destroy_step_9')
                ->pause(1000)
                ->click('@dialog-agree')
                ->pause(1000 * 20)
                ->screenshot('SchoolSection_destroy_step_10')
                ->assertNotPresent('.v-snack.v-snack--active');
        });
    }
}
