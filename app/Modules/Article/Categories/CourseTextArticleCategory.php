<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Categories;

use Cache;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Article\Actions\Admin\ArticleGetAction;
use App\Modules\Course\Models\Course;
use App\Modules\Article\Contracts\ArticleCategory;

/**
 * Абстрактный класс для создания собственного драйвера принятия текста.
 */
class CourseTextArticleCategory extends ArticleCategory
{
    /**
     * Название категории.
     *
     * @return string Название категории.
     */
    public function name(): string
    {
        return 'Курс / Описание';
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

            $course = $articleEntity->articleable;
            $course->text = $articleEntity->text;

            Course::find($articleEntity->articleable->id)->update($course->toArray());
            Cache::tags(['course'])->flush();
        } else {
            throw new RecordNotExistException(
                trans('course::actions.admin.courseUpdateStatusAction.notExistCourse')
            );
        }
    }
}
