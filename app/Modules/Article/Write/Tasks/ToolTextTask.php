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
use App\Modules\Tool\Models\Tool;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Article\Entities\Article as ArticleEntity;
use App\Modules\Article\Models\Article;
use App\Modules\Article\Enums\Status as ArticleStatus;

/**
 * Написание текстов для описания инструмента.
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
     * Запуск написания текстов.
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

            $entity = new ArticleEntity();
            $entity->category = 'tool.text';
            $entity->status = ArticleStatus::PENDING;
            $entity->articleable_id = $tool->id;
            $entity->articleable_type = 'App\Modules\Tool\Models\Tool';

            $article = Article::create($entity->toArray());
            $job = ArticleWriteTextJob::dispatch($article->id, 'tool.text');

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
        return Tool::where('status', true)
            ->doesntHave('articles', 'and', function (Builder $query) {
                $query->where('articles.category', 'tool.text');
            })
            ->orderBy('id', 'ASC');
    }
}
