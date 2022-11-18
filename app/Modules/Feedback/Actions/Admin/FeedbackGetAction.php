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
use ReflectionException;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Rep\RepositoryQueryBuilder;
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
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Метод запуска логики.
     *
     * @return FeedbackEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
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

                    return $feedback ? new FeedbackEntity($feedback->toArray()) : null;
                }
            );
        }

        return null;
    }
}

