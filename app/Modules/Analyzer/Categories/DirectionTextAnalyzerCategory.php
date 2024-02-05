<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Categories;

use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Direction\Actions\Admin\DirectionGetAction;
use App\Modules\Analyzer\Contracts\AnalyzerCategory;

/**
 * Анализатор текста для описаний направления.
 */
class DirectionTextAnalyzerCategory extends AnalyzerCategory
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
     * Получение текста для анализа.
     *
     * @param int $id ID сущности для которой проводится анализ.
     *
     * @return string|null Текст для проверки.
     * @throws RecordNotExistException
     */
    public function text(int $id): ?string
    {
        $action = new DirectionGetAction($id);
        $directionEntity = $action->run();

        if ($directionEntity) {
            return $directionEntity->text;
        } else {
            throw new RecordNotExistException(
                trans('direction::actions.admin.directionUpdateStatusAction.notExistDirection')
            );
        }
    }
}
