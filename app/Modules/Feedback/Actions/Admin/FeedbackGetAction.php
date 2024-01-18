<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Actions\Admin;

use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Feedback\Models\Feedback;
use App\Modules\Feedback\Entities\Feedback as FeedbackEntity;

/**
 * Класс действия для получения записи обратной связи.
 */
class FeedbackGetAction extends Action
{
    /**
     * ID пользователей.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return FeedbackEntity|null Вернет результаты исполнения.
     */
    public function run(): ?FeedbackEntity
    {
        if ($this->id) {
            $cacheKey = Util::getKey('feedback', $this->id);

            return Cache::tags(['feedback'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () {
                    $feedback = Feedback::find($this->id);

                    return $feedback ? FeedbackEntity::from($feedback->toArray()) : null;
                }
            );
        }

        return null;
    }
}

