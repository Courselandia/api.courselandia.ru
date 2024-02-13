<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Analyze\Tasks;

use Carbon\Carbon;
use App\Modules\Analyzer\Jobs\AnalyzerAnalyzeTextJob;
use App\Modules\Tool\Models\Tool;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Analyzer\Entities\Analyzer as AnalyzerEntity;
use App\Modules\Analyzer\Models\Analyzer;
use App\Modules\Analyzer\Enums\Status as AnalyzerStatus;

/**
 * Анализ текстов для описания инструментов.
 */
class ToolTextTask extends Task
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
     */
    public function run(Carbon $delay = null): void
    {
        $tools = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($tools as $tool) {
            $this->fireEvent('run', [$tool]);

            $entity = new AnalyzerEntity();
            $entity->category = 'tool.text';
            $entity->status = AnalyzerStatus::PENDING;
            $entity->analyzerable_id = $tool->id;
            $entity->analyzerable_type = 'App\Modules\Tool\Models\Tool';

            $analyzer = Analyzer::create($entity->toArray());
            $job = AnalyzerAnalyzeTextJob::dispatch($analyzer->id, 'tool.text');

            if ($delay) {
                $delay = $delay->addMinute();
                $job->delay($delay);
            }
        }
    }

    /**
     * Получить запрос на инструменты, у которых нет результатов анализа текста-описаний.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Tool::where('status', true)
            ->doesntHave('analyzers', 'and', function (Builder $query) {
                $query->where('analyzers.category', 'tool.text');
            })
            ->orderBy('id', 'ASC');
    }
}
