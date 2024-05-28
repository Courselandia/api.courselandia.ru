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
use App\Modules\Section\Models\Section;
use App\Modules\Article\Actions\Admin\ArticleMoveAnalyzer;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Article\Actions\Admin\ArticleGetAction;
use App\Modules\Section\Actions\Admin\SectionGetAction;
use App\Modules\Article\Contracts\ArticleCategory;

/**
 * Класс-драйвер написания и принятия текста для разделов.
 */
class SectionTextArticleCategory extends ArticleCategory
{
    /**
     * Название категории.
     *
     * @return string Название категории.
     */
    public function name(): string
    {
        return 'Раздел / Описание';
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
        $action = new SectionGetAction($id);
        $sectionEntity = $action->run();

        if ($sectionEntity) {
            return $sectionEntity->name;
        }

        throw new RecordNotExistException(
            trans('section::actions.admin.sectionUpdateStatusAction.notExistSection')
        );
    }

    /**
     * Метод для создания собственной логики принятия текста.
     *
     * @param int $id ID статьи.
     *
     * @return void
     * @throws RecordNotExistException
     */
    public function apply(int $id): void
    {
        $action = new ArticleGetAction($id);
        $articleEntity = $action->run();

        if ($articleEntity) {
            $section = $articleEntity->articleable;
            $section['text'] = Typography::process($articleEntity->text);

            Section::find($section['id'])->update($section);

            if ($articleEntity->analyzers) {
                $action = new ArticleMoveAnalyzer($section['id'], $articleEntity->analyzers, 'section.text', Section::class);
                $action->run();
            }

            Cache::tags(['section', 'article'])->flush();
        } else {
            throw new RecordNotExistException(
                trans('section::actions.admin.sectionUpdateStatusAction.notExistSection')
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
        $action = new SectionGetAction($id);
        $sectionEntity = $action->run();

        if ($sectionEntity) {
            return 'Напиши статью размером 500 символов для раздела "' . $sectionEntity->name . '".';
        }

        throw new RecordNotExistException(
            trans('section::actions.admin.sectionUpdateStatusAction.notExistSection')
        );
    }
}
