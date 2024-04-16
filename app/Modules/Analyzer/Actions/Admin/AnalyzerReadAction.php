<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Actions\Admin;

use Cache;
use Util;
use AnalyzerCategory;
use App\Models\Action;
use ReflectionException;
use App\Models\Enums\CacheTime;
use App\Modules\Analyzer\Models\Analyzer;
use App\Modules\Analyzer\Entities\Analyzer as AnalyzerEntity;

/**
 * Класс действия для чтения данных об анализе.
 */
class AnalyzerReadAction extends Action
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
            'analyzer',
            'admin',
            'read',
            'count',
            $this->sorts,
            $this->filters,
            $this->offset,
            $this->limit,
        );

        return Cache::tags(['analyzer'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = Analyzer::filter($this->filters ?: [])
                    ->with([
                        'analyzerable',
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
                    $field = AnalyzerCategory::driver($items[$i]['category'])->field();
                    $items[$i]['category_name'] = AnalyzerCategory::driver($items[$i]['category'])->name();
                    $items[$i]['category_label'] = AnalyzerCategory::driver($items[$i]['category'])->label($items[$i]['analyzerable_id']);
                    $items[$i]['text'] = $items[$i]['analyzerable'][$field];
                    $items[$i]['analyzerable_status'] = $items[$i]['analyzerable']['status'];
                    $items[$i]['analyzerable'] = null;
                }

                return [
                    'data' => AnalyzerEntity::collect($items),
                    'total' => $queryCount->count(),
                ];
            }
        );
    }
}
