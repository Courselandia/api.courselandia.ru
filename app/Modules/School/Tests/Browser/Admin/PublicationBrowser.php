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
 * Тестирование UI: Класс контроллер для тестирование школ.
 */
class SchoolBrowser extends DuskTestCase
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
            $school = School::factory()->create();

            $test = $this->getLogin($browser, 'School', 'index')
                ->screenshot('School_admin_index_step_6')
                ->visit('/dashboard/schools')
                ->pause(1000 * 30)
                ->screenshot('School_admin_index_step_7')
                ->assertPresent('.v-data-table TABLE TBODY TR TD.text-start')
                ->type('INPUT[name=search]', $school["name"]);

            $this->selectAutocomplete($browser, '#toolbar .v-autocomplete');

            $test->pause(1000 * 30)
                ->screenshot('School_admin_index_step_8')
                ->assertPresent('.v-data-table TABLE TBODY TR TD.text-start')
                ->click('@active')
                ->pause(1000)
                ->click('@dialog-agree')
                ->pause(1000 * 30)
                ->screenshot('School_admin_index_step_9')
                ->assertPresent('@deactivated')
                ->click('@deactivated')
                ->pause(1000)
                ->click('@dialog-agree')
                ->pause(1000 * 30)
                ->screenshot('School_admin_index_step_10')
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
            SchoolSection::factory()->create();

            $test = $this->getLogin($browser, 'School', 'create')
                ->screenshot('School_admin_create_step_6')
                ->visit('/dashboard/schools')
                ->pause(1000 * 30)
                ->screenshot('School_admin_create_step_7')
                ->click('@add')
                ->pause(1000 * 30)
                ->screenshot('School_admin_create_step_8');

            $this->selectAutocomplete($browser, "#section .v-autocomplete");
            $test->pause(1000 * 3);

            $this->selectDatePicker($browser, 'INPUT[name=published_date_at]');
            $test->pause(1000 * 3);

            $this->selectTimePicker($browser, 'INPUT[name=published_time_at]');
            $test->pause(1000 * 3);

            $this->typeInCKEditor($browser, '.ckeditor IFRAME', $faker->text(1000));

            $test->screenshot('School_admin_create_step_9')
                ->type('INPUT[name=header]', $faker->name)
                ->type('TEXTAREA[name=anons]', $faker->text(100))
                ->screenshot('School_admin_create_step_10')
                ->click('@send')
                ->screenshot('School_admin_create_step_11')
                ->pause(1000 * 30)
                ->screenshot('School_admin_create_step_12')
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
            School::factory()->create();

            $test = $this->getLogin($browser, 'School', 'update')
                ->screenshot('School_admin_update_step_6')
                ->visit('/dashboard/schools')
                ->pause(1000 * 30)
                ->screenshot('School_admin_update_step_7')
                ->click('@edit')
                ->pause(1000 * 30)
                ->screenshot('School_admin_update_step_8')
                ->click('@reset')
                ->screenshot('School_admin_update_step_9')
                ->scrollIntoView('.headline.mb-4');

            $this->selectAutocomplete($browser, "#section .v-autocomplete");
            $test->pause(1000 * 3);

            $test->screenshot('School_admin_update_step_10');

            $this->selectDatePicker($browser, 'INPUT[name=published_date_at]');
            $test->pause(1000 * 3);

            $test->screenshot('School_admin_update_step_11');

            $this->selectTimePicker($browser, 'INPUT[name=published_time_at]', 0, 0, true);
            $test->pause(1000 * 3);

            $test->screenshot('School_admin_update_step_12');

            $this->typeInCKEditor($browser, '.ckeditor IFRAME', $faker->text(1000));

            $test->screenshot('School_admin_update_step_13');

            $test
                ->type('INPUT[name=header]', $faker->name)
                ->type('TEXTAREA[name=anons]', $faker->text(100))
                ->screenshot('School_admin_update_step_14')
                ->click('@send')
                ->screenshot('School_admin_update_step_15')
                ->pause(1000 * 30)
                ->screenshot('School_admin_update_step_16')
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

            $test = $this->getLogin($browser, 'School', 'destroy')
                ->screenshot('School_admin_destroy_step_6')
                ->visit('/dashboard/schools')
                ->pause(1000 * 30)
                ->screenshot('School_admin_destroy_step_7')
                ->click('#toolbar BUTTON[aria-label="clear icon"]');

            $this->clear($browser, 'INPUT[name=search]');

            $test->pause(1000 * 30)
                ->screenshot('School_admin_destroy_step_8')
                ->click('@delete')
                ->pause(1000 * 30)
                ->screenshot('School_admin_destroy_step_9')
                ->pause(1000)
                ->click('@dialog-agree')
                ->pause(1000 * 20)
                ->screenshot('School_admin_destroy_step_10')
                ->assertNotPresent('.v-snack.v-snack--active');
        });
    }
}
