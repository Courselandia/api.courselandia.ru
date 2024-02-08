<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Write\Tasks;

use Carbon\Carbon;
use App\Modules\Article\Jobs\ArticleWriteTextJob;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Article\Entities\Article as ArticleEntity;
use App\Modules\Article\Models\Article;
use App\Modules\Article\Enums\Status as ArticleStatus;

/**
 * Написание текстов для описания курсов.
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
     * Запуск написания текстов.
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

            $entity = new ArticleEntity();
            $entity->category = 'course.text';
            $entity->status = ArticleStatus::PENDING;
            $entity->articleable_id = $course->id;
            $entity->articleable_type = 'App\Modules\Course\Models\Course';

            $article = Article::create($entity->toArray());
            $job = ArticleWriteTextJob::dispatch($article->id, 'course.text');

            if ($delay) {
                $delay = $delay->addMinute();
                $job->delay($delay);
            }
        }
    }

    /**
     * Получить запрос на курсы, у которых нет написанных статей для описания.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Course::where('status', Status::ACTIVE->value)
            ->doesntHave('articles', 'and', function (Builder $query) {
                $query->where('articles.category', 'course.text');
            })
            ->orderBy('id', 'ASC');
    }
}
