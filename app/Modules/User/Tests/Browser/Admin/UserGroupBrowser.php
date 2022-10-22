<?php
/**
 * Модуль Пользователи.
 * Этот модуль содержит все классы для работы с пользователями, авторизации и аутентификации в системе.
 *
 * @package App\Modules\User
 */

namespace App\Modules\User\Tests\Browser\Admin;

use App\Models\Test\HelpAdminBrowser;
use App\Modules\User\Models\UserGroup;
use Throwable;
use Tests\DuskTestCase;

/**
 * Тестирование UI: Класс контроллер для тестирования групп пользователей.
 */
class UserGroupBrowser extends DuskTestCase
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
            $userGroup = UserGroup::factory()->create([
                "name" => "Zlast index"
            ]);

            $this->getLogin($browser, 'UserGroup', 'index')
                ->screenshot('UserGroup_admin_index_step_6')
                ->visit('/dashboard/users/groups')
                ->pause(1000 * 30)
                ->screenshot('UserGroup_admin_index_step_7')
                ->assertPresent('.v-data-table TABLE TBODY TR TD.text-start')
                ->type('INPUT[name=search]', $userGroup["name"])
                ->pause(1000 * 30)
                ->screenshot('UserGroup_admin_index_step_8')
                ->assertPresent('.v-data-table TABLE TBODY TR TD.text-start')
                ->click("@active")
                ->pause(1000)
                ->click('@dialog-agree')
                ->pause(1000 * 30)
                ->screenshot('UserGroup_admin_index_step_9')
                ->assertPresent('@deactivated')
                ->click("@deactivated")
                ->pause(1000)
                ->click('@dialog-agree')
                ->pause(1000 * 30)
                ->screenshot('UserGroup_admin_index_step_10')
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
            $test = $this->getLogin($browser, 'UserGroup', 'create')
                ->screenshot('UserGroup_admin_create_step_6')
                ->visit('/dashboard/users/groups')
                ->pause(1000 * 30)
                ->screenshot('UserGroup_admin_create_step_7')
                ->click('@add')
                ->pause(1000 * 30)
                ->screenshot('UserGroup_admin_create_step_8')
                ->type('INPUT[name=name]', "Zlast create")
                ->screenshot('UserGroup_admin_create_step_9');

            $this->selectAutocomplete($browser, "#roles .v-autocomplete");

            $test->screenshot('UserGroup_admin_create_step_10')
                ->click('@send')
                ->screenshot('UserGroup_admin_create_step_11')
                ->pause(1000 * 30)
                ->screenshot('UserGroup_admin_create_step_12')
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
            UserGroup::factory()->create([
                "name" => "Zlast update"
            ]);

            $test = $this->getLogin($browser, 'UserGroup', 'update')
                ->screenshot('UserGroup_admin_update_step_6')
                ->visit('/dashboard/users/groups')
                ->pause(1000 * 30)
                ->screenshot('UserGroup_admin_update_step_7');

            $this->clear($browser, 'INPUT[name=search]')
                ->pause(1000 * 30);

            $test->elements('@edit')[3]->click();

            $test->pause(1000 * 30)
                ->screenshot('UserGroup_admin_update_step_8')
                ->click('@reset')
                ->type('INPUT[name=name]', "Zlast update")
                ->screenshot('UserGroup_admin_update_step_9');

            $this->selectAutocomplete($browser, "#roles .v-autocomplete", 2);

            $test->screenshot('UserGroup_admin_update_step_10')
                ->click('@send')
                ->screenshot('UserGroup_admin_update_step_11')
                ->pause(1000 * 30)
                ->screenshot('UserGroup_admin_update_step_12')
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
            UserGroup::factory()->create();

            $test = $this->getLogin($browser, 'UserGroup', 'destroy')
                ->screenshot('UserGroup_admin_destroy_step_6')
                ->visit('/dashboard/users/groups')
                ->pause(1000 * 30)
                ->screenshot('UserGroup_admin_destroy_step_7');

            $this->clear($browser, 'INPUT[name=search]');

            $test->pause(1000 * 30)
                ->screenshot('UserGroup_admin_destroy_step_8')
                ->click('@delete')
                ->pause(1000 * 30)
                ->screenshot('UserGroup_admin_destroy_step_9')
                ->pause(1000)
                ->click('@dialog-agree')
                ->pause(1000 * 20)
                ->screenshot('UserGroup_admin_destroy_step_10')
                ->assertNotPresent('.v-snack.v-snack--active');
        });
    }
}
