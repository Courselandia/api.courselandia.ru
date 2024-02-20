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
use App\Modules\Skill\Models\Skill;
use App\Modules\Article\Actions\Admin\ArticleMoveAnalyzer;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Article\Actions\Admin\ArticleGetAction;
use App\Modules\Skill\Actions\Admin\SkillGetAction;
use App\Modules\Article\Contracts\ArticleCategory;

/**
 * Класс-драйвер написания и принятия текста для навыков.
 */
class SkillTextArticleCategory extends ArticleCategory
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
            $skill = $articleEntity->articleable;
            $skill['text'] = Typography::process($articleEntity->text);

            Skill::find($skill['id'])->update($skill);

            if ($articleEntity->analyzers) {
                $action = new ArticleMoveAnalyzer($skill['id'], $articleEntity->analyzers, 'skill.text', Skill::class);
                $action->run();
            }

            Cache::tags(['skill', 'article'])->flush();
        } else {
            throw new RecordNotExistException(
                trans('skill::actions.admin.skillUpdateStatusAction.notExistSkill')
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
        $action = new SkillGetAction($id);
        $skillEntity = $action->run();

        if ($skillEntity) {
            return 'Напиши статью размером 500 символов о навыке "' . $skillEntity->name . '".';
        } else {
            throw new RecordNotExistException(
                trans('skill::actions.admin.skillUpdateStatusAction.notExistSkill')
            );
        }
    }
}
