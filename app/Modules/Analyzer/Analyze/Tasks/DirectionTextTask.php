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
use App\Modules\Direction\Models\Direction;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Analyzer\Entities\Analyzer as AnalyzerEntity;
use App\Modules\Analyzer\Models\Analyzer;
use App\Modules\Analyzer\Enums\Status as AnalyzerStatus;

/**
 * Анализ текстов для описания направления.
 */
class DirectionTextTask extends Task
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
        $directions = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($directions as $direction) {
            $this->fireEvent('run', [$direction]);

            $entity = new AnalyzerEntity();
            $entity->category = 'direction.text';
            $entity->status = AnalyzerStatus::PENDING;
            $entity->analyzerable_id = $direction->id;
            $entity->analyzerable_type = 'App\Modules\Direction\Models\Direction';

            $analyzer = Analyzer::create($entity->toArray());
            $job = AnalyzerAnalyzeTextJob::dispatch($analyzer->id, 'direction.text');

            if ($delay) {
                $delay = $delay->addMinute();
                $job->delay($delay);
            }
        }
    }

    /**
     * Получить запрос на направления, у которых нет результатов анализа текста-описаний.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Direction::where('status', true)
            ->doesntHave('analyzers', 'and', function (Builder $query) {
                $query->where('analyzers.category', 'direction.text');
            })
            ->orderBy('id', 'ASC');
    }
}
