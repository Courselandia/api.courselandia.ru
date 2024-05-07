<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Analyze;

use App\Models\Event;
use App\Models\Error;
use App\Modules\Analyzer\Actions\Admin\AnalyzerAnalyzeAction;
use App\Modules\Analyzer\Models\Analyzer;
use App\Modules\Article\Enums\Status as ArticleStatus;
use App\Modules\Course\Enums\Status as CourseStatus;
use Illuminate\Database\Eloquent\Builder;
use Throwable;

/**
 * Класс для массового повторного анализа.
 */
class Reanalyze
{
    use Event;
    use Error;

    /**
     * Список категорий для фильтрации.
     *
     * @var ?array
     */
    private ?array $categories;

    /**
     * Список статусов для фильтрации.
     *
     * @var ?array
     */
    private ?array $statuses;

    /**
     * Признак того, что сущность активна или не активна.
     *
     * @var ?bool
     */
    private ?bool $active;

    /**
     * Отфильтровать статьи по верхнему порогу заспамленности.
     *
     * @var int|null
     */
    private ?int $unique;

    /**
     * Отфильтровать статьи по нижнему порогу количества воды.
     *
     * @var int|null
     */
    private ?int $water;

    /**
     * Отфильтровать статьи по нижнему порогу заспамленности.
     *
     * @var int|null
     */
    private ?int $spam;

    /**
     * @param array|null $categories Список категорий для фильтрации.
     * @param array|null $statuses Список статусов для фильтрации.
     * @param bool|null $active Признак того, что сущность активна или не активна.
     * @param int|null $unique Отфильтровать статьи по верхнему порогу заспамленности.
     * @param int|null $water Отфильтровать статьи по нижнему порогу количества воды.
     * @param int|null $spam Отфильтровать статьи по нижнему порогу заспамленности.
     */
    public function __construct(
        ?array $categories = null,
        ?array $statuses = null,
        ?bool $active = null,
        ?int $unique = null,
        ?int $water = null,
        ?int $spam = null,
    ) {
        $this->categories = $categories;
        $this->statuses = $statuses;
        $this->active = $active;
        $this->unique = $unique;
        $this->water = $water;
        $this->spam = $spam;
    }

    /**
     * Метод запуска логики.
     */
    public function run(): void
    {
        $this->offLimits();
        $this->do();
    }

    /**
     * Получить количество заданий на переанализ.
     *
     * @return int Общее количество заданий.
     */
    public function getTotal(): int
    {
        return $this->getQuery()->count();
    }

    /**
     * Запуск заданий.
     *
     * @return void
     */
    private function do(): void
    {
        $items = $this
            ->getQuery()
            ->get();

        foreach ($items as $item) {
            try {
                $action = new AnalyzerAnalyzeAction($item->id);
                $action->run();

                $this->fireEvent('run');
            } catch (Throwable $e) {
                $this->addError($e);
            }
        }
    }

    /**
     * Отключение лимитов.
     *
     * @return void
     */
    private function offLimits(): void
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        ignore_user_abort(true);
    }

    /**
     * Получить запрос на выборку.
     *
     * @return Builder
     */
    private function getQuery(): Builder
    {
        $query = Analyzer::query();

        if ($this->categories) {
            $query->whereIn('category', $this->categories);
        }

        if ($this->statuses) {
            $query->whereIn('status', $this->statuses);
        }

        if ($this->unique) {
            $query->where('unique', '<=', $this->unique);
        }

        if ($this->water) {
            $query->where('water', '>=', $this->water);
        }

        if ($this->spam) {
            $query->where('spam', '>=', $this->spam);
        }

        if (isset($this->active)) {
            $query->whereHas('analyzerable', function (Builder $query) {
                $statusValue = [
                    CourseStatus::ACTIVE->value,
                    ArticleStatus::APPLIED,
                    ArticleStatus::READY,
                    1,
                ];

                if ($this->active) {
                    return $query->whereIn('status', $statusValue);
                } else {
                    return $query->whereNotIn('status', $statusValue);
                }
            });
        }

        return $query;
    }
}