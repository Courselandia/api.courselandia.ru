<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Actions\Site\School;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\School\Entities\School as SchoolEntity;
use App\Modules\School\Models\School;
use Cache;
use ReflectionException;
use Util;

/**
 * Класс действия для чтения школ.
 */
class SchoolReadAction extends Action
{
    /**
     * Сортировка данных.
     *
     * @var array|null
     */
    private ?array $sorts;

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
     * @param int|null $offset Начать выборку.
     * @param int|null $limit Лимит выборки выборку.
     */
    public function __construct(
        array  $sorts = null,
        ?int   $offset = null,
        ?int   $limit = null
    )
    {
        $this->sorts = $sorts;
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
            'school',
            'site',
            'read',
            'count',
            $this->sorts,
            $this->offset,
            $this->limit,
            'metatag'
        );

        return Cache::tags(['catalog', 'school'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = School::sorted($this->sorts ?: [])
                    ->active()
                    ->with([
                        'metatag',
                    ])
                    ->withCount('reviews');

                $queryCount = $query->clone();

                if ($this->offset) {
                    $query->offset($this->offset);
                }

                if ($this->limit) {
                    $query->limit($this->limit);
                }

                $items = $query->get()->toArray();

                return [
                    'data' => SchoolEntity::collect($items),
                    'total' => $queryCount->count(),
                ];
            }
        );
    }
}
