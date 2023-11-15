<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Categories;

use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Teacher\Actions\Admin\Teacher\TeacherGetAction;
use App\Modules\Analyzer\Contracts\AnalyzerCategory;

/**
 * Анализатор текста для описаний учителя.
 */
class TeacherTextAnalyzerCategory extends AnalyzerCategory
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
     * Название колонки, которая хранит текст, что должен быть проанализирован.
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
     * @param int $id ID сущности для которой производится анализ.
     *
     * @return string Метка.
     * @throws RecordNotExistException|ParameterInvalidException
     */
    public function label(int $id): string
    {
        $action = app(TeacherGetAction::class);
        $action->id = $id;
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
     * Получение текста для анализа.
     *
     * @param int $id ID сущности для которой проводится анализ.
     *
     * @return string|null Текст для проверки.
     * @throws RecordNotExistException|ParameterInvalidException
     */
    public function text(int $id): ?string
    {
        $action = app(TeacherGetAction::class);
        $action->id = $id;
        $teacherEntity = $action->run();

        if ($teacherEntity) {
            return $teacherEntity->text;
        } else {
            throw new RecordNotExistException(
                trans('teacher::actions.admin.teacherUpdateStatusAction.notExistTeacher')
            );
        }
    }
}
