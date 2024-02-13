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
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID статьи.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
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
            ArticleCategory::driver($articleEntity->category)->apply($articleEntity->id);

            Cache::tags(['article'])->flush();

            $article = Article::where('id', $this->id)->first();

            $article->status = Status::APPLIED->value;
            $article->save();

            Cache::tags(['article'])->flush();

            $action = new ArticleGetAction($this->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('article::actions.admin.articleApplyAction.notExistArticle')
        );
    }
}
