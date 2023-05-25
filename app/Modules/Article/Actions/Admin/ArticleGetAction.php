<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Actions\Admin;

use Cache;
use Util;
use ArticleCategory;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Article\Entities\Article as ArticleEntity;
use App\Modules\Article\Models\Article;

/**
 * Класс действия для получения статьи.
 */
class ArticleGetAction extends Action
{
    /**
     * ID направления.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Метод запуска логики.
     *
     * @return ArticleEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?ArticleEntity
    {
        $cacheKey = Util::getKey('article', $this->id);

        return Cache::tags(['article'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $article = Article::where('id', $this->id)
                    ->with('articleable')
                    ->first();

                if ($article) {
                    $entity = new ArticleEntity($article->toArray());
                    $field = ArticleCategory::driver($entity->category)->field();
                    $entity->category_name = ArticleCategory::driver($entity->category)->name();
                    $entity->category_label = ArticleCategory::driver($entity->category)->label($article->articleable_id);
                    $entity->text_current = $entity->articleable->{$field};
                    $entity->request_template = ArticleCategory::driver($entity->category)->requestTemplate($article->articleable_id);

                    return $entity;
                }

                return null;
            }
        );
    }
}
