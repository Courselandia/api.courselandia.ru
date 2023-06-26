<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Actions\Admin;

use Cache;
use ArticleCategory;
use App\Models\Action;
use App\Modules\Article\Enums\Status;
use App\Modules\Article\Models\Article;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Article\Entities\Article as ArticleEntity;

/**
 * Класс действия для принятия и переноса написанного текста в сущность, для которой он был написан.
 */
class ArticleApplyAction extends Action
{
    /**
     * ID статьи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

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
            ArticleCategory::driver($articleEntity->category)->apply($articleEntity->id);

            Cache::tags(['article'])->flush();

            $action = app(ArticleGetAction::class);
            $action->id = $this->id;
            $articleEntity = $action->run();
            $articleEntity->status = Status::APPLIED;

            Article::find($this->id)->update($articleEntity->toArray());

            Cache::tags(['article'])->flush();

            $action = app(ArticleGetAction::class);
            $action->id = $this->id;

            return $action->run();

        }

        throw new RecordNotExistException(
            trans('article::actions.admin.articleApplyAction.notExistArticle')
        );
    }
}
