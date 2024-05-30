<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Actions\Admin;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Widget\Entities\Widget as WidgetEntity;
use App\Modules\Widget\Models\Widget;
use Cache;
use ReflectionException;
use Util;

/**
 * Класс действия для чтения виджетов.
 */
class WidgetReadAction extends Action
{
    /**
     * Сортировка данных.
     *
     * @var array|null
     */
    private ?array $sorts;

    /**
     * Фильтрация данных.
     *
     * @var array|null
     */
    private ?array $filters;

    /**
     * Начать выборку.
     *
     * @var int|null
     */
    private ?int $offset;

    /**
     * Лимит выборки выборку.
     *
     * @var int|null
     */
    private ?int $limit;

    /**
     * @param array|null $sorts Сортировка данных.
     * @param array|null $filters Фильтрация данных.
     * @param int|null $offset Начать выборку.
     * @param int|null $limit Лимит выборки выборку.
     */
    public function __construct(
        array  $sorts = null,
        ?array $filters = null,
        ?int   $offset = null,
        ?int   $limit = null
    )
    {
        $this->sorts = $sorts;
        $this->filters = $filters;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    /**
     * Метод запуска логики.
     *
     * @return mixed Вернет результаты исполнения.
     * @throws ReflectionException
     */
    public function run(): array
    {
        $cacheKey = Util::getKey(
            'widget',
            'admin',
            'read',
            'count',
            $this->sorts,
            $this->filters,
            $this->offset,
            $this->limit,
        );

        return Cache::tags(['widget'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Widget::filter($this->filters ?: [])
                    ->with([
                        'values',
                    ]);

                $queryCount = $query->clone();

                $query->sorted($this->sorts ?: []);

                if ($this->offset) {
                    $query->offset($this->offset);
                }

                if ($this->limit) {
                    $query->limit($this->limit);
                }

                $items = $query->get()->toArray();

                return [
                    'data' => WidgetEntity::collect($items),
                    'total' => $queryCount->count(),
                ];
            }
        );
    }
}
