<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Tests\Browser\Admin;

use App\Models\Test\HelpAdminBrowser;
use App\Modules\Feedback\Models\Feedback;
use Throwable;
use Tests\DuskTestCase;

/**
 * Тестирование UI: Класс контроллер для тестирования обратной связи.
 */
class FeedbackBrowser extends DuskTestCase
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
            $feedback = Feedback::factory()->create();

            $this->getLogin($browser, 'Feedback', 'index')
                ->screenshot('Feedback_admin_index_step_6')
                ->visit('/dashboard/feedbacks')
                ->pause(1000 * 30)
                ->screenshot('Feedback_admin_index_step_7')
                ->assertPresent('.v-data-table TABLE TBODY TR TD.text-start')
                ->type('INPUT[name=search]', $feedback["name"])
                ->pause(1000 * 30)
                ->screenshot('Feedback_admin_index_step_8')
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
            Feedback::factory()->create();

            $this->getLogin($browser, 'Feedback', 'update')
                ->screenshot('Feedback_admin_update_step_6')
                ->visit('/dashboard/feedbacks')
                ->pause(1000 * 30)
                ->screenshot('Feedback_admin_update_step_7')
                ->click('@edit')
                ->pause(1000 * 30)
                ->screenshot('Feedback_admin_update_step_8')
                ->assertPresent('@name');
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
            Feedback::factory()->create();

            $test = $this->getLogin($browser, 'Feedback', 'destroy')
                ->screenshot('Feedback_admin_destroy_step_6')
                ->visit('/dashboard/feedbacks')
                ->pause(1000 * 30)
                ->screenshot('Feedback_admin_destroy_step_7');

            $this->clear($browser, 'INPUT[name=search]');

            $test->pause(1000 * 30)
                ->screenshot('Feedback_admin_destroy_step_8')
                ->click('@delete')
                ->pause(1000 * 30)
                ->screenshot('Feedback_admin_destroy_step_9')
                ->pause(1000)
                ->click('@dialog-agree')
                ->pause(1000 * 20)
                ->screenshot('Feedback_admin_destroy_step_10')
                ->assertNotPresent('.v-snack.v-snack--active');
        });
    }
}
