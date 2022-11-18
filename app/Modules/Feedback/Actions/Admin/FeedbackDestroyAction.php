<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Actions\Admin;

use App\Modules\Feedback\Models\Feedback;
use App\Models\Action;
use Cache;

/**
 * Класс действия для удаления обратной связи.
 */
class FeedbackDestroyAction extends Action
{
    /**
     * Массив ID пользователей.
     *
     * @var int[]|string[]
     */
    public ?array $ids = null;

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     */
    public function run(): bool
    {
        if ($this->ids) {
            Feedback::destroy($this->ids);
        }

        Cache::tags(['feedback'])->flush();

        return true;
    }
}
