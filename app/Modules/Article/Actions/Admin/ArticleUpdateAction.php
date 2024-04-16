<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Actions\Admin;

use Cache;
use Typography;
use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;
use App\Modules\Article\Enums\Status;
use App\Modules\Article\Entities\Article as ArticleEntity;
use App\Modules\Article\Models\Article;

/**
 * Класс действия для обновления статьи.
 */
class ArticleUpdateAction extends Action
{
    /**
     * ID статьи.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * Статья.
     *
     * @var string
     */
    private string $text;

    /**
     * Принять.
     *
     * @var bool
     */
    private bool $apply;

    /**
     * @param int|string $id
     * @param string $text
     * @param bool $apply
     */
    public function __construct(int|string $id, string $text, bool $apply)
    {
        $this->id = $id;
        $this->text = $text;
        $this->apply = $apply;
    }

    /**
     * Метод запуска логики.
     *
     * @return ArticleEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): ArticleEntity
    {
        $action = new ArticleGetAction($this->id);
        $articleEntity = $action->run();

        if ($articleEntity) {
            $articleEntity->text = Typography::process($this->text);
            $articleEntity->status = Status::READY;

            Article::find($this->id)->update($articleEntity->toArray());

            if ($this->apply) {
                $action = new ArticleApplyAction($this->id);
                $action->run();
            }

            $action = new AnalyzerUpdateAction($this->id, Article::class, 'article.text');
            $action->run();

            Cache::tags(['article'])->flush();

            $action = new ArticleGetAction($this->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('article::actions.admin.articleUpdateAction.notExistArticle')
        );
    }
}
