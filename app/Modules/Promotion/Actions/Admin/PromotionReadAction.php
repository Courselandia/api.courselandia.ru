<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Actions\Admin;

use Cache;
use Throwable;
use Util;
use ReflectionException;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Promotion\Entities\Promotion as PromotionEntity;
use App\Modules\Promotion\Models\Promotion;

/**
 * Класс действия для чтения промоакций.
 */
class PromotionReadAction extends Action
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
        array $sorts = null,
        ?array $filters = null,
        ?int $offset = null,
        ?int $limit = null
    ) {
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
     * @throws Throwable
     */
    public function run(): array
    {
        $cacheKey = Util::getKey(
            'promotion',
            'admin',
            'read',
            'count',
            $this->sorts,
            $this->filters,
            $this->offset,
            $this->limit,
            'school',
        );

        return Cache::tags(['catalog', 'school'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Promotion::filter($this->filters ?: [])
                    ->with([
                        'school',
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

                for ($i = 0; $i < count($items); $i++) {
                    $action = new PromotionApplicableAction($items[$i]['id']);
                    $items[$i]['applicable'] = $action->run();
                }

                return [
                    'data' => PromotionEntity::collect($items),
                    'total' => $queryCount->count(),
                ];
            }
        );
    }
}
