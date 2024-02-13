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
use App\Modules\Category\Models\Category;
use App\Modules\Article\Actions\Admin\ArticleMoveAnalyzer;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Article\Actions\Admin\ArticleGetAction;
use App\Modules\Category\Actions\Admin\CategoryGetAction;
use App\Modules\Article\Contracts\ArticleCategory;

/**
 * Класс-драйвер написания и принятия текста для категории.
 */
class CategoryTextArticleCategory extends ArticleCategory
{
    /**
     * Название категории.
     *
     * @return string Название категории.
     */
    public function name(): string
    {
        return 'Категория / Описание';
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
        $action = new CategoryGetAction($id);
        $categoryEntity = $action->run();

        if ($categoryEntity) {
            return $categoryEntity->name;
        } else {
            throw new RecordNotExistException(
                trans('category::actions.admin.categoryUpdateStatusAction.notExistCategory')
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
     * @throws ParameterInvalidException
     */
    public function apply(int $id): void
    {
        $action = new ArticleGetAction($id);
        $articleEntity = $action->run();

        if ($articleEntity) {
            $category = $articleEntity->articleable;
            $category['text'] = Typography::process($articleEntity->text);

            Category::find($articleEntity->articleable['id'])->update($category);

            if ($articleEntity->analyzers) {
                $action = new ArticleMoveAnalyzer($category['id'], $articleEntity->analyzers, 'category.text', Category::class);
                $action->run();
            }

            Cache::tags(['category', 'article'])->flush();
        } else {
            throw new RecordNotExistException(
                trans('category::actions.admin.categoryUpdateStatusAction.notExistCategory')
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
        $action = new CategoryGetAction($id);
        $categoryEntity = $action->run();

        if ($categoryEntity) {
            return 'Напиши статью размером 500 символов о категории курсов "' . $categoryEntity->name . '".';
        } else {
            throw new RecordNotExistException(
                trans('category::actions.admin.categoryUpdateStatusAction.notExistCategory')
            );
        }
    }
}
