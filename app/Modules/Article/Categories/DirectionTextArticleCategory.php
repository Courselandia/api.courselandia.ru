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
use App\Modules\Direction\Models\Direction;
use App\Modules\Article\Actions\Admin\ArticleMoveAnalyzer;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Article\Actions\Admin\ArticleGetAction;
use App\Modules\Direction\Actions\Admin\DirectionGetAction;
use App\Modules\Article\Contracts\ArticleCategory;

/**
 * Класс-драйвер написания и принятия текста для направлений.
 */
class DirectionTextArticleCategory extends ArticleCategory
{
    /**
     * Название категории.
     *
     * @return string Название категории.
     */
    public function name(): string
    {
        return 'Направление / Описание';
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
        $action = new DirectionGetAction($id);
        $directionEntity = $action->run();

        if ($directionEntity) {
            return $directionEntity->name;
        } else {
            throw new RecordNotExistException(
                trans('direction::actions.admin.directionUpdateStatusAction.notExistDirection')
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
            $direction = $articleEntity->articleable;
            $direction['text'] = Typography::process($articleEntity->text);

            Direction::find($direction['id'])->update($direction);

            if ($articleEntity->analyzers) {
                $action = new ArticleMoveAnalyzer($direction['id'], $articleEntity->analyzers, 'direction.text', Direction::class);
                $action->run();
            }

            Cache::tags(['direction', 'article'])->flush();
        } else {
            throw new RecordNotExistException(
                trans('direction::actions.admin.directionUpdateStatusAction.notExistDirection')
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
        $action = new DirectionGetAction($id);
        $directionEntity = $action->run();

        if ($directionEntity) {
            return 'Напиши статью размером 500 символов о направление "' . $directionEntity->name . '".';
        } else {
            throw new RecordNotExistException(
                trans('direction::actions.admin.directionUpdateStatusAction.notExistDirection')
            );
        }
    }
}
