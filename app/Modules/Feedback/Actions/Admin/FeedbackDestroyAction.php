<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Actions\Admin;

use App\Modules\Feedback\Repositories\Feedback;
use App\Models\Action;
use Cache;

/**
 * Класс действия для удаления обратной связи.
 */
class FeedbackDestroyAction extends Action
{
    /**
     * Репозиторий обратной связи.
     *
     * @var Feedback
     */
    private Feedback $feedback;

    /**
     * Массив ID пользователей.
     *
     * @var int[]|string[]
     */
    public ?array $ids = null;

    /**
     * Конструктор.
     *
     * @param  Feedback  $feedback  Репозиторий обратной связи.
     */
    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        if ($this->ids) {
            for ($i = 0; $i < count($this->ids); $i++) {
                $this->feedback->destroy($this->ids[$i]);
            }
        }

        Cache::tags(['feedback'])->flush();

        return true;
    }
}
