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
use App\Modules\Feedback\Repositories\Feedback;
use App\Modules\Feedback\Entities\Feedback as FeedbackEntity;

/**
 * Класс действия для получения записи обратной связи.
 */
class FeedbackGetAction extends Action
{
    /**
     * Репозиторий обратной связи.
     *
     * @var Feedback
     */
    private Feedback $feedback;

    /**
     * ID пользователей.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

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
     * @return FeedbackEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function run(): ?FeedbackEntity
    {
        if ($this->id) {
            $query = new RepositoryQueryBuilder($this->id);
            $cacheKey = Util::getKey('feedback', $query);

            return Cache::tags(['feedback'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($query) {
                    return $this->feedback->get($query);
                }
            );
        }

        return null;
    }
}

