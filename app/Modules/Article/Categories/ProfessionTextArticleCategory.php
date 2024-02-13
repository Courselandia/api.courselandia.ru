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
use App\Modules\Profession\Models\Profession;
use App\Modules\Article\Actions\Admin\ArticleMoveAnalyzer;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Article\Actions\Admin\ArticleGetAction;
use App\Modules\Profession\Actions\Admin\ProfessionGetAction;
use App\Modules\Article\Contracts\ArticleCategory;

/**
 * Класс-драйвер написания и принятия текста для профессии.
 */
class ProfessionTextArticleCategory extends ArticleCategory
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
            $profession = $articleEntity->articleable;
            $profession['text'] = Typography::process($articleEntity->text);

            Profession::find($articleEntity->articleable['id'])->update($profession);

            if ($articleEntity->analyzers) {
                $action = new ArticleMoveAnalyzer($profession['id'], $articleEntity->analyzers, 'profession.text', Profession::class);
                $action->run();
            }

            Cache::tags(['profession', 'article'])->flush();
        } else {
            throw new RecordNotExistException(
                trans('profession::actions.admin.professionUpdateStatusAction.notExistProfession')
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
        $action = new ProfessionGetAction($id);
        $professionEntity = $action->run();

        if ($professionEntity) {
            return 'Напиши статью размером 500 символов о профессии "' . $professionEntity->name . '".';
        } else {
            throw new RecordNotExistException(
                trans('profession::actions.admin.professionUpdateStatusAction.notExistProfession')
            );
        }
    }
}
