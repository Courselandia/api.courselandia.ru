<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Rewrite\Tasks;

use Carbon\Carbon;
use App\Modules\Collection\Models\Collection;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Article\Enums\Status as ArticleStatus;
use App\Modules\Article\Models\Article;
use App\Modules\Article\Jobs\ArticleRewriteTextJob;

/**
 * Переписывание текстов для описания навыка.
 */
class CollectionTextTask extends Task
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
        $collections = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($collections as $collection) {
            $this->fireEvent('run', [$collection]);

            /**
             * @var Article $article
             */
            $article = $collection->articles[0];
            $article->task_id = null;
            $article->request = null;
            $article->tries = 0;
            $article->status = ArticleStatus::PENDING->value;

            $article->save();

            $job = ArticleRewriteTextJob::dispatch($article->id, 'collection.text', $article->text, $this->creative);

            if ($delay) {
                $delay = $delay->addMinute();
                $job->delay($delay);
            }
        }
    }

    /**
     * Получить запрос на навыки, у которых нет написанных статей для описания.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Collection::whereHas('articles', function ($query) {
            $query->whereIn('status',
                [
                    ArticleStatus::READY->value,
                    ArticleStatus::FAILED->value,
                    ArticleStatus::DISABLED->value,
                    ArticleStatus::APPLIED->value,
                ]
            )
            ->where('category', 'collection.text')
            ->where(function ($query) {
                $query->whereNotNull('text')
                    ->orWhere('text', '!=', '');
            })
            ->whereHas('analyzers', function ($query) {
                $query->where('category', 'article.text');
                if ($this->unique) {
                    $query->where('unique', '<', $this->unique);
                }

                if ($this->water) {
                    $query->where('water', '>', $this->water);
                }

                if ($this->spam) {
                    $query->where('spam', '>', $this->water);
                }
            });
        })
        ->with('articles', function ($query) {
            $query->where('category', 'collection.text');
        })
        ->with('articles.analyzers', function ($query) {
            $query->where('category', 'article.text');
        })
        ->orderBy('id', 'ASC');
    }
}
