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
use App\Modules\Profession\Actions\Admin\ProfessionGetAction;
use App\Modules\Analyzer\Contracts\AnalyzerCategory;

/**
 * Анализатор текста для описаний профессии.
 */
class ProfessionTextAnalyzerCategory extends AnalyzerCategory
{
    /**
     * Название категории.
     *
     * @return string Название категории.
     */
    public function name(): string
    {
        return 'Профессия / Описание';
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
        $action = new ProfessionGetAction($id);
        $professionEntity = $action->run();

        if ($professionEntity) {
            return $professionEntity->name;
        } else {
            throw new RecordNotExistException(
                trans('profession::actions.admin.professionUpdateStatusAction.notExistProfession')
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
        $action = new ProfessionGetAction($id);
        $professionEntity = $action->run();

        if ($professionEntity) {
            return $professionEntity->text;
        } else {
            throw new RecordNotExistException(
                trans('profession::actions.admin.professionUpdateStatusAction.notExistProfession')
            );
        }
    }
}
