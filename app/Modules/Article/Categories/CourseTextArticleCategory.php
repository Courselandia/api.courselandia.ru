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
use App\Modules\Course\Models\Course;
use App\Modules\Article\Actions\Admin\ArticleMoveAnalyzer;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Article\Actions\Admin\ArticleGetAction;
use App\Modules\Course\Actions\Admin\Course\CourseGetAction;
use App\Modules\Article\Contracts\ArticleCategory;

/**
 * Класс-драйвер написания и принятия текста для курсов.
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
     * Метод для получения метки, которая характеризует сущность.
     *
     * @param int $id ID сущности для которой пишется статья.
     *
     * @return string Метка.
     * @throws RecordNotExistException
     */
    public function label(int $id): string
    {
        $action = new CourseGetAction($id);
        $courseEntity = $action->run();

        if ($courseEntity) {
            return $courseEntity->name;
        } else {
            throw new RecordNotExistException(
                trans('course::actions.admin.courseUpdateStatusAction.notExistCourse')
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
            $course = $articleEntity->articleable;
            $course['text'] = Typography::process($articleEntity->text);

            Course::find($articleEntity->articleable['id'])->update($course);

            if ($articleEntity->analyzers) {
                $action = new ArticleMoveAnalyzer($course['id'], $articleEntity->analyzers, 'course.text', Course::class);
                $action->run();
            }

            Cache::tags(['course', 'article'])->flush();
        } else {
            throw new RecordNotExistException(
                trans('course::actions.admin.courseUpdateStatusAction.notExistCourse')
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
        $action = new CourseGetAction($id);
        $courseEntity = $action->run();

        if ($courseEntity) {
            $request = 'Напиши текст описание для курса "' . $courseEntity->name . '" от школы "' . $courseEntity->school->name . '". ';
            $request .= 'Текст нужно писать от лица агрегатора курсов. Текст должен содержать 500 символов. ';

            if (count($courseEntity->directions)) {
                $request .= 'Направление курса ' . mb_strtolower($courseEntity->directions[0]->name) . '. ';
            }

            if (count($courseEntity->professions)) {
                $request .= 'После окончания курса студент освоит ';

                if (count($courseEntity->professions) === 1) {
                    $request .= 'профессию ' . mb_strtolower($courseEntity->professions[0]->name);
                } else {
                    $request .= 'профессии ';

                    for ($i = 0; $i < count($courseEntity->professions); $i++) {
                        if ($i !== 0) {
                            $request .= ', ';
                        }

                        $request .= $courseEntity->professions[$i]->name;
                    }
                }

                $request .= '. ';
            }

            if ($courseEntity->modules_amount) {
                $request .= 'Курс состоит из ' . $courseEntity->modules_amount . ' ';

                if ($courseEntity->modules_amount === 1) {
                    $request .= 'модуля';
                } else {
                    $request .= 'модулей';
                }

                $request .= '. ';
            }

            if ($courseEntity->lessons_amount) {
                $request .= 'Курс состоит из ' . $courseEntity->lessons_amount . ' ';

                if ($courseEntity->lessons_amount === 1) {
                    $request .= 'урока';
                } else if ($courseEntity->lessons_amount >= 2 && $courseEntity->lessons_amount <= 4) {
                    $request .= 'урока';
                } else {
                    $request .= 'уроков';
                }

                $request .= '. ';
            }

            if ($courseEntity->text) {
                $request .= 'Текст, который можно использовать: ' . html_entity_decode(strip_tags($courseEntity->text));
            }

            return $request;
        } else {
            throw new RecordNotExistException(
                trans('course::actions.admin.courseUpdateStatusAction.notExistCourse')
            );
        }
    }
}
