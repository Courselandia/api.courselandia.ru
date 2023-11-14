<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Write\Tasks;

use Carbon\Carbon;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Article\Jobs\ArticleWriteTextJob;
use App\Modules\Profession\Models\Profession;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Article\Entities\Article as ArticleEntity;
use App\Modules\Article\Models\Article;
use App\Modules\Article\Enums\Status as ArticleStatus;

/**
 * Написание текстов для описания профессий.
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
     * Запуск написания текстов.
     *
     * @param Carbon|null $delay Дата, на сколько нужно отложить задачу.
     *
     * @return void
     * @throws ParameterInvalidException
     */
    public function run(Carbon $delay = null): void
    {
        $professions = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($professions as $profession) {
            $this->fireEvent('run', [$profession]);

            $entity = new ArticleEntity();
            $entity->category = 'profession.text';
            $entity->status = ArticleStatus::PENDING;
            $entity->articleable_id = $profession->id;
            $entity->articleable_type = 'App\Modules\Profession\Models\Profession';

            $article = Article::create($entity->toArray());
            $job = ArticleWriteTextJob::dispatch($article->id, 'profession.text');

            if ($delay) {
                $delay = $delay->addMinute();
                $job->delay($delay);
            }
        }
    }

    /**
     * Получить запрос на проефссии, у которых нет написанных статей для описания.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Profession::where('status', true)
            ->doesntHave('articles', 'and', function (Builder $query) {
                $query->where('articles.category', 'profession.text');
            })
            ->orderBy('id', 'ASC');
    }
}
