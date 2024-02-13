<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Actions\Admin;

use Auth;
use Cache;
use Writer;
use App\Models\Action;
use App\Modules\Task\Jobs\Launcher;
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
     * @var int|string
     */
    private int|string $id;

    /**
     * Запрос на переписания.
     *
     * @var string
     */
    private string $request;

    /**
     * @param int|string $id
     * @param string $request
     */
    public function __construct(int|string $id, string $request)
    {
        $this->id = $id;
        $this->request = $request;
    }

    /**
     * Метод запуска логики.
     *
     * @return ArticleEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     */
    public function run(): ArticleEntity
    {
        $action = new ArticleGetAction($this->id);
        $articleEntity = $action->run();

        if ($articleEntity) {
            $taskId = Writer::request($this->request);
            $articleEntity->task_id = $taskId;
            $articleEntity->request = $this->request;
            $articleEntity->status = Status::PROCESSING;

            Article::find($this->id)->update($articleEntity->toArray());

            Launcher::dispatch(
                'Переписание статьи',
                new ArticleSaveResultJob($this->id),
                Auth::getUser() ? Auth::getUser()->id : null,
            )->delay(now()->addMinutes(2));

            Cache::tags(['article'])->flush();

            $action = new ArticleGetAction($this->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('article::actions.admin.articleUpdateAction.notExistArticle')
        );
    }
}
