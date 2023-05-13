<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Actions\Admin;

use Cache;
use Writer;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Article\Enums\Status;
use App\Modules\Article\Entities\Article as ArticleEntity;
use App\Modules\Article\Models\Article;
use App\Modules\Article\Jobs\ArticleSaveResultJob;

/**
 * Класс действия для переписания статьи.
 */
class ArticleRewriteAction extends Action
{
    /**
     * ID статьи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Запрос на переписания.
     *
     * @var string|null
     */
    public ?string $request = null;

    /**
     * Метод запуска логики.
     *
     * @return ArticleEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     */
    public function run(): ArticleEntity
    {
        $action = app(ArticleGetAction::class);
        $action->id = $this->id;
        $articleEntity = $action->run();

        if ($articleEntity) {
            $taskId = Writer::write($this->request);
            $articleEntity->task_id = $taskId;
            $articleEntity->request = $this->request;
            $articleEntity->status = Status::PROCESSING;

            Article::find($this->id)->update($articleEntity->toArray());

            ArticleSaveResultJob::dispatch($this->id)
                ->delay(now()->addMinutes(2));

            Cache::tags(['article'])->flush();

            $action = app(ArticleGetAction::class);
            $action->id = $this->id;

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('article::actions.admin.articleUpdateAction.notExistArticle')
        );
    }
}
