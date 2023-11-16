<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Analyze\Tasks;

use Carbon\Carbon;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Analyzer\Jobs\AnalyzerAnalyzeTextJob;
use App\Modules\Category\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Analyzer\Entities\Analyzer as AnalyzerEntity;
use App\Modules\Analyzer\Models\Analyzer;
use App\Modules\Analyzer\Enums\Status as AnalyzerStatus;

/**
 * Анализ текстов для описания категорий.
 */
class CategoryTextTask extends Task
{
    /**
     * Количество запускаемых заданий.
     *
     * @return int Количество.
     */
    public function count(): int
    {
         return $this->getQuery()->count();
    }

    /**
     * Запуск анализа текстов.
     *
     * @param Carbon|null $delay Дата, на сколько нужно отложить задачу.
     *
     * @return void
     * @throws ParameterInvalidException
     */
    public function run(Carbon $delay = null): void
    {
        $categories = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($categories as $category) {
            $this->fireEvent('run', [$category]);

            $entity = new AnalyzerEntity();
            $entity->category = 'category.text';
            $entity->status = AnalyzerStatus::PENDING;
            $entity->analyzerable_id = $category->id;
            $entity->analyzerable_type = 'App\Modules\Category\Models\Category';

            $analyzer = Analyzer::create($entity->toArray());
            $job = AnalyzerAnalyzeTextJob::dispatch($analyzer->id, 'category.text');

            if ($delay) {
                $delay = $delay->addMinute();
                $job->delay($delay);
            }
        }
    }

    /**
     * Получить запрос на категории, у которых нет результатов анализа текста-описаний.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Category::where('status', true)
            ->doesntHave('analyzers', 'and', function (Builder $query) {
                $query->where('analyzers.category', 'category.text');
            })
            ->orderBy('id', 'ASC');
    }
}
