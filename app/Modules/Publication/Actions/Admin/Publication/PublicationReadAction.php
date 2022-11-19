<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Actions\Admin\Publication;

use App\Models\Action;
use App\Models\Entity;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Publication\Entities\Publication as PublicationEntity;
use App\Modules\Publication\Models\Publication;
use Cache;
use JetBrains\PhpStorm\ArrayShape;
use ReflectionException;
use Util;

/**
 * Класс действия для чтения публикаций.
 */
class PublicationReadAction extends Action
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
            'publication',
            'admin',
            'read',
            'count',
            $this->sorts,
            $this->filters,
            $this->offset,
            $this->limit,
            'metatag',
        );

        return Cache::tags(['publication'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Publication::filter($this->filters ?: [])
                    ->sorted($this->sorts ?: [])
                    ->with([
                        'metatag',
                    ]);

                if ($this->offset) {
                    $query->offset($this->offset);
                }

                if ($this->limit) {
                    $query->limit($this->limit);
                }

                $items = $query->get()->toArray();

                return [
                    'data' => Entity::toEntities($items, new PublicationEntity()),
                    'total' => $query->count(),
                ];
            }
        );
    }
}
