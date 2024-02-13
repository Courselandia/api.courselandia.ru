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
use App\Modules\Teacher\Models\Teacher;
use App\Modules\Article\Actions\Admin\ArticleMoveAnalyzer;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Article\Actions\Admin\ArticleGetAction;
use App\Modules\Teacher\Actions\Admin\Teacher\TeacherGetAction;
use App\Modules\Article\Contracts\ArticleCategory;

/**
 * Класс-драйвер написания и принятия текста для учителей.
 */
class TeacherTextArticleCategory extends ArticleCategory
{
    /**
     * Название категории.
     *
     * @return string Название категории.
     */
    public function name(): string
    {
        return 'Учитель / Описание';
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
        $action = new TeacherGetAction($id);
        $teacherEntity = $action->run();

        if ($teacherEntity) {
            return $teacherEntity->name;
        } else {
            throw new RecordNotExistException(
                trans('teacher::actions.admin.teacherUpdateStatusAction.notExistTeacher')
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
            $teacher = $articleEntity->articleable;
            $teacher['text'] = Typography::process($articleEntity->text);
            $teacher['copied'] = false;

            Teacher::find($articleEntity->articleable['id'])->update($teacher);

            if ($articleEntity->analyzers) {
                $action = new ArticleMoveAnalyzer($teacher['id'], $articleEntity->analyzers, 'teacher.text', Teacher::class);
                $action->run();
            }

            Cache::tags(['teacher', 'article'])->flush();
        } else {
            throw new RecordNotExistException(
                trans('teacher::actions.admin.teacherUpdateStatusAction.notExistTeacher')
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
        $action = new TeacherGetAction($id);
        $teacherEntity = $action->run();

        if ($teacherEntity) {
            return 'Перепиши биографию учителя ' . ' ' . $teacherEntity->name . ': ' . $teacherEntity->text;
        } else {
            throw new RecordNotExistException(
                trans('teacher::actions.admin.teacherUpdateStatusAction.notExistTeacher')
            );
        }
    }
}
