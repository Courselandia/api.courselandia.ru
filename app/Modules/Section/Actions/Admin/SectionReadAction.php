<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Actions\Admin;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Section\Entities\Section as SectionEntity;
use App\Modules\Section\Models\Section;
use Cache;
use ReflectionException;
use Util;
use Config;

/**
 * Класс действия для чтения раздела.
 */
class SectionReadAction extends Action
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
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): array
    {
        $cacheKey = Util::getKey(
            'section',
            'admin',
            'read',
            'count',
            $this->sorts,
            $this->filters,
            $this->offset,
            $this->limit,
            'metatag',
        );

        return Cache::tags(['catalog', 'section'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Section::filter($this->filters ?: [])
                    ->with([
                        'metatag',
                        'items.itemable',
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
                $sections = SectionEntity::collection($items);
                $configItems = Config::get('section.items');

                foreach ($sections as $section) {
                    foreach ($section->items as $item) {
                        $item->type = array_search($item->itemable_type, $configItems);
                    }
                }

                return [
                    'data' => $sections,
                    'total' => $queryCount->count(),
                ];
            }
        );
    }
}
