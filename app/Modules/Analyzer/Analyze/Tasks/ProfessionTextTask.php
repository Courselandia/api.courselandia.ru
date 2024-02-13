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
use App\Modules\Profession\Models\Profession;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Analyzer\Entities\Analyzer as AnalyzerEntity;
use App\Modules\Analyzer\Models\Analyzer;
use App\Modules\Analyzer\Enums\Status as AnalyzerStatus;

/**
 * Анализ текстов для описания профессии.
 */
class ProfessionTextTask extends Task
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
        $professions = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($professions as $profession) {
            $this->fireEvent('run', [$profession]);

            $entity = new AnalyzerEntity();
            $entity->category = 'profession.text';
            $entity->status = AnalyzerStatus::PENDING;
            $entity->analyzerable_id = $profession->id;
            $entity->analyzerable_type = 'App\Modules\Profession\Models\Profession';

            $analyzer = Analyzer::create($entity->toArray());
            $job = AnalyzerAnalyzeTextJob::dispatch($analyzer->id, 'profession.text');

            if ($delay) {
                $delay = $delay->addMinute();
                $job->delay($delay);
            }
        }
    }

    /**
     * Получить запрос на профессии, у которых нет результатов анализа текста-описаний.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Profession::where('status', true)
            ->doesntHave('analyzers', 'and', function (Builder $query) {
                $query->where('analyzers.category', 'profession.text');
            })
            ->orderBy('id', 'ASC');
    }
}
