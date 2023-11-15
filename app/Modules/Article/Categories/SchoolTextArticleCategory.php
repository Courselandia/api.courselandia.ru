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
use App\Modules\School\Models\School;
use App\Modules\Article\Actions\Admin\ArticleMoveAnalyzer;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Article\Actions\Admin\ArticleGetAction;
use App\Modules\Article\Contracts\ArticleCategory;
use App\Modules\School\Actions\Admin\School\SchoolGetAction;

/**
 * Класс-драйвер написания и принятия текста для школы.
 */
class SchoolTextArticleCategory extends ArticleCategory
{
    /**
     * Название категории.
     *
     * @return string Название категории.
     */
    public function name(): string
    {
        return 'Школа / Описание';
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
        $action = app(SchoolGetAction::class);
        $action->id = $id;
        $schoolEntity = $action->run();

        if ($schoolEntity) {
            return $schoolEntity->name;
        } else {
            throw new RecordNotExistException(
                trans('school::actions.admin.schoolUpdateStatusAction.notExistSchool')
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
            $school = $articleEntity->articleable;
            $school->text = Typography::process($articleEntity->text);

            School::find($articleEntity->articleable->id)->update($school->toArray());

            if ($articleEntity->analyzers) {
                $action = new ArticleMoveAnalyzer($school->id, $articleEntity->analyzers, 'school.text', School::class);
                $action->run();
            }

            Cache::tags(['school', 'article'])->flush();
        } else {
            throw new RecordNotExistException(
                trans('school::actions.admin.schoolUpdateStatusAction.notExistSchool')
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
        $action = app(SchoolGetAction::class);
        $action->id = $id;
        $schoolEntity = $action->run();

        if ($schoolEntity) {
            return 'Напиши статью размером 500 символов об онлайн-школе "' . $schoolEntity->name . '".';
        } else {
            throw new RecordNotExistException(
                trans('school::actions.admin.schoolUpdateStatusAction.notExistSchool')
            );
        }
    }
}
