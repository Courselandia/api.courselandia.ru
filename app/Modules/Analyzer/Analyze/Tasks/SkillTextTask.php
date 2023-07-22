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
use App\Modules\Skill\Models\Skill;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Analyzer\Entities\Analyzer as AnalyzerEntity;
use App\Modules\Analyzer\Models\Analyzer;
use App\Modules\Analyzer\Enums\Status as AnalyzerStatus;

/**
 * Анализ текстов для описания навыка.
 */
class SkillTextTask extends Task
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
        $skills = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($skills as $skill) {
            $this->fireEvent('run', [$skill]);

            $entity = new AnalyzerEntity();
            $entity->category = 'skill.text';
            $entity->status = AnalyzerStatus::PENDING;
            $entity->analyzerable_id = $skill->id;
            $entity->analyzerable_type = 'App\Modules\Skill\Models\Skill';

            $analyzer = Analyzer::create($entity->toArray());
            $job = AnalyzerAnalyzeTextJob::dispatch($analyzer->id, 'skill.text');

            if ($delay) {
                $delay = $delay->addMinute();
                $job->delay($delay);
            }
        }
    }

    /**
     * Получить запрос на курсы, у которых нет результатов анализа текста-описаний для курса.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Skill::where('status', true)
            ->doesntHave('analyzers', 'and', function (Builder $query) {
                $query->where('analyzers.category', 'skill.text');
            })
            ->orderBy('id', 'ASC');
    }
}
