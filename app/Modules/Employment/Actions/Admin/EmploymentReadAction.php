<?php
/**
 * Модуль Трудоустройство.
 * Этот модуль содержит все классы для работы с трудоустройствами.
 *
 * @package App\Modules\Employment
 */

namespace App\Modules\Employment\Actions\Admin;

use App\Models\Action;
use App\Models\Entity;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Employment\Entities\Employment as EmploymentEntity;
use App\Modules\Employment\Models\Employment;
use Cache;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionException;
use Util;

/**
 * Класс действия для чтения трудоустройства.
 */
class EmploymentReadAction extends Action
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
     * @return mixed Вернет результаты исполнения.
     * @throws ParameterInvalidException|ReflectionException
     */
    #[ArrayShape(['data' => 'array', 'total' => 'int'])] public function run(): array
    {
        $cacheKey = Util::getKey(
            'employment',
            'admin',
            'read',
            'count',
            $this->sorts,
            $this->filters,
            $this->offset,
            $this->limit,
        );

        return Cache::tags(['catalog', 'employment'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Employment::filter($this->filters ?: [])
                    ->sorted($this->sorts ?: []);

                $queryCount = $query->clone();

                if ($this->offset) {
                    $query->offset($this->offset);
                }

                if ($this->limit) {
                    $query->limit($this->limit);
                }

                $items = $query->get()->toArray();

                return [
                    'data' => Entity::toEntities($items, new EmploymentEntity()),
                    'total' => $queryCount->count(),
                ];
            }
        );
    }
}
