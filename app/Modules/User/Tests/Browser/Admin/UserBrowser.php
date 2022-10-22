<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Tests\Browser\Admin;

use App\Models\Test\HelpAdminBrowser;
use App\Modules\User\Models\User;
use Faker\Factory as Faker;
use Throwable;
use Tests\DuskTestCase;

/**
 * Тестирование UI: Класс контроллер для тестирования пользователей.
 */
class UserBrowser extends DuskTestCase
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
            $user = User::factory()->create();

            $test = $this->getLogin($browser, 'User', 'index')
                ->screenshot('User_admin_index_step_6')
                ->visit('/dashboard/users')
                ->pause(1000 * 30)
                ->screenshot('User_admin_index_step_7')
                ->assertPresent('.v-data-table TABLE TBODY TR TD.text-start')
                ->type('INPUT[name=search]', $user["name"])
                ->pause(1000 * 30)
                ->screenshot('User_admin_index_step_8')
                ->assertPresent('.v-data-table TABLE TBODY TR TD.text-start');

            $test->elements("@active")[1]->click();

            $test->pause(1000)
                ->click('@dialog-agree')
                ->pause(1000 * 30)
                ->screenshot('User_admin_index_step_9')
                ->assertPresent('@deactivated');

            $test->elements("@deactivated")[0]->click();

            $test->pause(1000)
                ->click('@dialog-agree')
                ->pause(1000 * 30)
                ->screenshot('User_admin_index_step_10')
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

            $test = $this->getLogin($browser, 'User', 'create')
                ->screenshot('User_admin_create_step_6')
                ->visit('/dashboard/users')
                ->pause(1000 * 30)
                ->screenshot('User_admin_create_step_7')
                ->click('@add')
                ->pause(1000 * 30)
                ->screenshot('User_admin_create_step_8')
                ->type('INPUT[name=login]', $faker->email)
                ->type('INPUT[name=password]', $faker->password)
                ->type('INPUT[name=first_name]', $faker->firstName)
                ->type('INPUT[name=second_name]', $faker->lastName)
                ->screenshot('User_admin_create_step_9');

            $this->selectAutocomplete($browser, "#groups .v-autocomplete");

            $test->screenshot('User_admin_create_step_10')
                ->click('@send')
                ->screenshot('User_admin_create_step_11')
                ->pause(1000 * 30)
                ->screenshot('User_admin_create_step_12')
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
            User::factory()->create();

            $test = $this->getLogin($browser, 'User', 'update')
                ->screenshot('User_admin_update_step_6')
                ->visit('/dashboard/users')
                ->pause(1000 * 30)
                ->screenshot('User_admin_update_step_7');

            $test->elements('@edit')[1]->click();

            $test->pause(1000 * 30)
                ->screenshot('User_admin_update_step_8')
                ->click('@reset')
                ->type('INPUT[name=login]', $faker->email)
                ->type('INPUT[name=first_name]', $faker->firstName)
                ->type('INPUT[name=second_name]', $faker->lastName)
                ->screenshot('User_admin_update_step_9');

            $this->selectAutocomplete($browser, "#groups .v-autocomplete", 2);

            $test->screenshot('User_admin_update_step_10')
                ->click('@send')
                ->screenshot('User_admin_update_step_11')
                ->pause(1000 * 30)
                ->screenshot('User_admin_update_step_12')
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
            User::factory()->create();

            $test = $this->getLogin($browser, 'User', 'destroy')
                ->screenshot('User_admin_destroy_step_6')
                ->visit('/dashboard/users')
                ->pause(1000 * 30)
                ->screenshot('User_admin_destroy_step_7');

            $this->clear($browser, 'INPUT[name=search]');

            $test->pause(1000 * 30)
                ->screenshot('User_admin_destroy_step_8')
                ->click('@delete')
                ->pause(1000 * 30)
                ->screenshot('User_admin_destroy_step_9')
                ->pause(1000)
                ->click('@dialog-agree')
                ->pause(1000 * 20)
                ->screenshot('User_admin_destroy_step_10')
                ->assertNotPresent('.v-snack.v-snack--active');
        });
    }
}
