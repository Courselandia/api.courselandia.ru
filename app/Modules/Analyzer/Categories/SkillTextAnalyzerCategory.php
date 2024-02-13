<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Categories;

use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Skill\Actions\Admin\SkillGetAction;
use App\Modules\Analyzer\Contracts\AnalyzerCategory;

/**
 * Анализатор текста для описаний навыка.
 */
class SkillTextAnalyzerCategory extends AnalyzerCategory
{
    /**
     * Название категории.
     *
     * @return string Название категории.
     */
    public function name(): string
    {
        return 'Навык / Описание';
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
        $action = new SkillGetAction($id);
        $skillEntity = $action->run();

        if ($skillEntity) {
            return $skillEntity->name;
        } else {
            throw new RecordNotExistException(
                trans('skill::actions.admin.skillUpdateStatusAction.notExistSkill')
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
        $action = new SkillGetAction($id);
        $skillEntity = $action->run();

        if ($skillEntity) {
            return $skillEntity->text;
        } else {
            throw new RecordNotExistException(
                trans('skill::actions.admin.skillUpdateStatusAction.notExistSkill')
            );
        }
    }
}
