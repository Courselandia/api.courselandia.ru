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
use App\Modules\Direction\Models\Direction;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Article\Entities\Article as ArticleEntity;
use App\Modules\Article\Models\Article;
use App\Modules\Article\Enums\Status as ArticleStatus;

/**
 * Написание текстов для описания направления.
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
     * Запуск написания текстов.
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

            $entity = new ArticleEntity();
            $entity->category = 'direction.text';
            $entity->status = ArticleStatus::PENDING;
            $entity->articleable_id = $direction->id;
            $entity->articleable_type = 'App\Modules\Direction\Models\Direction';

            $article = Article::create($entity->toArray());
            $job = ArticleWriteTextJob::dispatch($article->id, 'direction.text');

            if ($delay) {
                $delay = $delay->addMinute();
                $job->delay($delay);
            }
        }
    }

    /**
     * Получить запрос на направления, у которых нет написанных статей для описания.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Direction::where('status', true)
            ->doesntHave('articles', 'and', function (Builder $query) {
                $query->where('articles.category', 'direction.text');
            })
            ->orderBy('id', 'ASC');
    }
}
