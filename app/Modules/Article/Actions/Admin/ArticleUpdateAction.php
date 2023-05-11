<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Article\Entities\Article as ArticleEntity;
use App\Modules\Article\Models\Article;
use Cache;

/**
 * Класс действия для обновления статьи.
 */
class ArticleUpdateAction extends Action
{
    /**
     * ID статьи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Статья.
     *
     * @var string|null
     */
    public ?string $text = null;

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
            $articleEntity->text = $this->text;

            Article::find($this->id)->update($articleEntity->toArray());

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
