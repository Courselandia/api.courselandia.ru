<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Categories;

use App\Models\Exceptions\RecordNotExistException;
use App\Modules\School\Actions\Admin\School\SchoolGetAction;
use App\Modules\Analyzer\Contracts\AnalyzerCategory;

/**
 * Анализатор текста для описаний школы.
 */
class SchoolTextAnalyzerCategory extends AnalyzerCategory
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
     * @throws RecordNotExistException
     */
    public function label(int $id): string
    {
        $action = new SchoolGetAction($id);
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
     * Получение текста для анализа.
     *
     * @param int $id ID сущности для которой проводится анализ.
     *
     * @return string|null Текст для проверки.
     * @throws RecordNotExistException
     */
    public function text(int $id): ?string
    {
        $action = new SchoolGetAction($id);
        $schoolEntity = $action->run();

        if ($schoolEntity) {
            return $schoolEntity->text;
        } else {
            throw new RecordNotExistException(
                trans('school::actions.admin.schoolUpdateStatusAction.notExistSchool')
            );
        }
    }
}
