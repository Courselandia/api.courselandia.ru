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
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Analyzer\Entities\Analyzer as AnalyzerEntity;
use App\Modules\Analyzer\Models\Analyzer;
use App\Modules\Analyzer\Enums\Status as AnalyzerStatus;

/**
 * Анализ текстов для описания курсов.
 */
class CourseTextTask extends Task
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
        $courses = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($courses as $course) {
            $this->fireEvent('run', [$course]);

            $entity = new AnalyzerEntity();
            $entity->category = 'course.text';
            $entity->status = AnalyzerStatus::PENDING;
            $entity->analyzerable_id = $course->id;
            $entity->analyzerable_type = 'App\Modules\Course\Models\Course';

            $analyzer = Analyzer::create($entity->toArray());
            $job = AnalyzerAnalyzeTextJob::dispatch($analyzer->id, 'course.text');

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
        return Course::where('status', Status::ACTIVE->value)
            ->doesntHave('analyzers', 'and', function (Builder $query) {
                $query->where('analyzers.category', 'course.text');
            })
            ->orderBy('id', 'ASC');
    }
}
