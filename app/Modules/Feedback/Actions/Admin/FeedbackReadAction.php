<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Actions\Admin;

use App\Models\Entity;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Feedback\Entities\Feedback as FeedbackEntity;
use App\Modules\Feedback\Models\Feedback;
use App\Models\Action;
use Cache;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionException;
use Util;

/**
 * Класс действия для чтения обратной связи.
 */
class FeedbackReadAction extends Action
{
    /**
     * Сортировка данных.
     *
     * @var array|null
     */
    public ?array $sorts = null;

    /**
     * Фильтрация данных.
     *
     * @var array|null
     */
    public ?array $filters = null;

    /**
     * Начать выборку.
     *
     * @var int|null
     */
    public ?int $offset = null;

    /**
     * Лимит выборки выборку.
     *
     * @var int|null
     */
    public ?int $limit = null;

    /**
     * Метод запуска логики.
     *
     * @return array Вернет результаты исполнения.
     * @throws ParameterInvalidException|ReflectionException
     */
    #[ArrayShape(['data' => 'array', 'total' => 'int'])] public function run(): array
    {
        $cacheKey = Util::getKey(
            'feedback',
            'admin',
            'read',
            'count',
            $this->filters,
            $this->offset,
            $this->limit,
        );

        return Cache::tags(['feedback'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function ()  {
                $query = Feedback::filter($this->filters ?: [])
                    ->sorted($this->sorts ?: []);

                if ($this->offset) {
                    $query->offset($this->offset);
                }

                if ($this->limit) {
                    $query->limit($this->limit);
                }

                $items = $query->get()->toArray();

                return [
                    'data' => Entity::toEntities($items, new FeedbackEntity()),
                    'total' => $query->count(),
                ];
            }
        );
    }
}
