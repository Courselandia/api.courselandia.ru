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
use App\Modules\School\Models\School;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Article\Entities\Article as ArticleEntity;
use App\Modules\Article\Models\Article;
use App\Modules\Article\Enums\Status as ArticleStatus;

/**
 * Написание текстов для описания школ.
 */
class SchoolTextTask extends Task
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
        $schools = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($schools as $school) {
            $this->fireEvent('run', [$school]);

            $entity = new ArticleEntity();
            $entity->category = 'school.text';
            $entity->status = ArticleStatus::PENDING;
            $entity->articleable_id = $school->id;
            $entity->articleable_type = 'App\Modules\School\Models\School';

            $article = Article::create($entity->toArray());
            $job = ArticleWriteTextJob::dispatch($article->id, 'school.text');

            if ($delay) {
                $delay = $delay->addMinute();
                $job->delay($delay);
            }
        }
    }

    /**
     * Получить запрос на школы, у которых нет написанных статей для описания.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return School::where('status', true)
            ->doesntHave('articles', 'and', function (Builder $query) {
                $query->where('articles.category', 'school.text');
            })
            ->orderBy('id', 'ASC');
    }
}
