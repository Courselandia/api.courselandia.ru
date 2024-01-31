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
     * Массив ID обратной связи.
     *
     * @var int[]|string[]
     */
    private array $ids;

    /**
     * @param array $ids Массив ID обратной связи.
     */
    public function __construct(array $ids)
    {
        $this->ids = $ids;
    }

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
