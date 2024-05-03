<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Actions\Admin;

use Cache;
use Throwable;
use Util;
use ReflectionException;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Promocode\Entities\Promocode as PromocodeEntity;
use App\Modules\Promocode\Models\Promocode;

/**
 * Класс действия для чтения промокодов.
 */
class PromocodeReadAction extends Action
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
            'promocode',
            'admin',
            'read',
            'count',
            $this->sorts,
            $this->filters,
            $this->offset,
            $this->limit,
            'school',
        );

        return Cache::tags(['promocode', 'school'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Promocode::filter($this->filters ?: [])
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
                    $action = new PromocodeApplicableAction($items[$i]['id']);
                    $items[$i]['applicable'] = $action->run();
                }

                return [
                    'data' => PromocodeEntity::collect($items),
                    'total' => $queryCount->count(),
                ];
            }
        );
    }
}
