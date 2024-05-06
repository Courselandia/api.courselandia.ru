<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Categories;

use Cache;
use Typography;
use App\Modules\Collection\Models\Collection;
use App\Modules\Article\Actions\Admin\ArticleMoveAnalyzer;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Article\Actions\Admin\ArticleGetAction;
use App\Modules\Collection\Actions\Admin\Collection\CollectionGetAction;
use App\Modules\Article\Contracts\ArticleCategory;

/**
 * Класс-драйвер написания и принятия текста для коллекции.
 */
class CollectionTextArticleCategory extends ArticleCategory
{
    /**
     * Название категории.
     *
     * @return string Название категории.
     */
    public function name(): string
    {
        return 'Коллекция / Описание';
    }

    /**
     * Название колонки, которая хранит текст, что должен быть изменен.
     *
     * @return string Название колонки.
     */
    public function field(): string
    {
        return 'text';
    }

    /**
     * Метод для получения метки, которая характеризует сущность.
     *
     * @param int $id ID сущности для которой пишется статья.
     *
     * @return string Метка.
     * @throws RecordNotExistException
     */
    public function label(int $id): string
    {
        $action = new CollectionGetAction($id);
        $collectionEntity = $action->run();

        if ($collectionEntity) {
            return $collectionEntity->name;
        } else {
            throw new RecordNotExistException(
                trans('collection::actions.admin.collectionUpdateStatusAction.notExistCollection')
            );
        }
    }

    /**
     * Метод для создания собственной логики принятия текста.
     *
     * @param int $id ID статьи.
     *
     * @return void
     * @throws RecordNotExistException
     */
    public function apply(int $id): void
    {
        $action = new ArticleGetAction($id);
        $articleEntity = $action->run();

        if ($articleEntity) {
            $collection = $articleEntity->articleable;
            $collection['text'] = Typography::process($articleEntity->text);
            $collection['copied'] = false;

            Collection::find($collection['id'])->update($collection);

            if ($articleEntity->analyzers) {
                $action = new ArticleMoveAnalyzer($collection['id'], $articleEntity->analyzers, 'collection.text', Collection::class);
                $action->run();
            }

            Cache::tags(['collection', 'article'])->flush();
        } else {
            throw new RecordNotExistException(
                trans('collection::actions.admin.collectionUpdateStatusAction.notExistCollection')
            );
        }
    }

    /**
     * Шаблон запроса к искусственному интеллекту.
     *
     * @param int $id ID сущности для которой пишется статья.
     *
     * @return string Запрос.
     * @throws RecordNotExistException
     */
    public function requestTemplate(int $id): string
    {
        $action = new CollectionGetAction($id);
        $collectionEntity = $action->run();

        if ($collectionEntity) {
            return 'Перепиши текст: ' . $collectionEntity->text . '.';
        } else {
            throw new RecordNotExistException(
                trans('collection::actions.admin.collectionUpdateStatusAction.notExistCollection')
            );
        }
    }
}
