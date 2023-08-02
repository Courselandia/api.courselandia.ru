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
use App\Modules\Tool\Models\Tool;
use App\Modules\Article\Actions\Admin\ArticleMoveAnalyzer;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Article\Actions\Admin\ArticleGetAction;
use App\Modules\Tool\Actions\Admin\ToolGetAction;
use App\Modules\Article\Contracts\ArticleCategory;

/**
 * Класс-драйвер написания и принятия текста для инструмента.
 */
class ToolTextArticleCategory extends ArticleCategory
{
    /**
     * Название категории.
     *
     * @return string Название категории.
     */
    public function name(): string
    {
        return 'Инструмент / Описание';
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
     * @throws RecordNotExistException|ParameterInvalidException
     */
    public function label(int $id): string
    {
        $action = app(ToolGetAction::class);
        $action->id = $id;
        $toolEntity = $action->run();

        if ($toolEntity) {
            return $toolEntity->name;
        } else {
            throw new RecordNotExistException(
                trans('tool::actions.admin.toolUpdateStatusAction.notExistTool')
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
        $action = app(ArticleGetAction::class);
        $action->id = $id;
        $articleEntity = $action->run();

        if ($articleEntity) {
            $tool = $articleEntity->articleable;
            $tool->text = Typography::process($articleEntity->text);

            Tool::find($articleEntity->articleable->id)->update($tool->toArray());

            if ($articleEntity->analyzers) {
                $action = new ArticleMoveAnalyzer($tool->id, $articleEntity->analyzers, 'tool.text', Tool::class);
                $action->run();
            }

            Cache::tags(['tool', 'article'])->flush();
        } else {
            throw new RecordNotExistException(
                trans('tool::actions.admin.toolUpdateStatusAction.notExistTool')
            );
        }
    }

    /**
     * Шаблон запроса к искусственному интеллекту.
     *
     * @param int $id ID сущности для которой пишется статья.
     *
     * @return string Запрос.
     * @throws RecordNotExistException|ParameterInvalidException
     */
    public function requestTemplate(int $id): string
    {
        $action = app(ToolGetAction::class);
        $action->id = $id;
        $toolEntity = $action->run();

        if ($toolEntity) {
            return 'Напиши статью размером 500 символов о "' . $toolEntity->name . '".';
        } else {
            throw new RecordNotExistException(
                trans('tool::actions.admin.toolUpdateStatusAction.notExistTool')
            );
        }
    }
}
