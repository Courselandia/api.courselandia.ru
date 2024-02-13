<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Actions\Admin;

use Cache;
use App\Models\Action;
use App\Modules\Article\Enums\Status;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Article\Entities\Article as ArticleEntity;
use App\Modules\Article\Models\Article;

/**
 * Класс действия для обновления статуса статьи.
 */
class ArticleUpdateStatusAction extends Action
{
    /**
     * ID направления.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * Статус.
     *
     * @var Status
     */
    private Status $status;

    /**
     * @param int|string $id ID направления.
     * @param Status $status Статус.
     */
    public function __construct(int|string $id, Status $status)
    {
        $this->id = $id;
        $this->status = $status;
    }

    /**
     * Метод запуска логики.
     *
     * @return ArticleEntity Вернет результаты исполнения.
     * @throws RecordNotExistException|ParameterInvalidException
     */
    public function run(): ArticleEntity
    {
        $action = new ArticleGetAction($this->id);
        $articleEntity = $action->run();

        if ($articleEntity) {
            $articleEntity->status = $this->status;

            Article::find($this->id)->update($articleEntity->toArray());
            Cache::tags(['article'])->flush();

            return $articleEntity;
        }

        throw new RecordNotExistException(
            trans('article::actions.admin.articleUpdateStatusAction.notExistArticle')
        );
    }
}
