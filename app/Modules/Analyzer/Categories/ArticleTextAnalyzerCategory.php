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
use App\Modules\Article\Actions\Admin\ArticleGetAction;
use App\Modules\Analyzer\Contracts\AnalyzerCategory;

/**
 * Анализатор текста для написанных статей ИИ.
 */
class ArticleTextAnalyzerCategory extends AnalyzerCategory
{
    /**
     * Название категории.
     *
     * @return string Название категории.
     */
    public function name(): string
    {
        return 'Статьи / Написанный текст';
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
        $action = app(ArticleGetAction::class);
        $action->id = $id;
        $articleEntity = $action->run();

        if ($articleEntity) {
            return $articleEntity->id;
        } else {
            throw new RecordNotExistException(
                trans('article::actions.admin.articleUpdateAction.notExistArticle')
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
        $action = app(ArticleGetAction::class);
        $action->id = $id;
        $articleEntity = $action->run();

        if ($articleEntity) {
            return $articleEntity->text;
        } else {
            throw new RecordNotExistException(
                trans('article::actions.admin.articleUpdateAction.notExistArticle')
            );
        }
    }
}
