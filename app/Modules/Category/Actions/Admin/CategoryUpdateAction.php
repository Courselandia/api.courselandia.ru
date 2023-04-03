<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Category\Entities\Category as CategoryEntity;
use App\Modules\Category\Models\Category;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use Cache;

/**
 * Класс действия для обновления категорий.
 */
class CategoryUpdateAction extends Action
{
    /**
     * ID категории.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Шаблон заголовка.
     *
     * @var string|null
     */
    public ?string $header_template = null;

    /**
     * Ссылка.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * Статья.
     *
     * @var string|null
     */
    public ?string $text = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Шаблон описания.
     *
     * @var string|null
     */
    public ?string $template_description = null;

    /**
     * Ключевые слова.
     *
     * @var string|null
     */
    public ?string $keywords = null;

    /**
     * Шаблон заголовка.
     *
     * @var string|null
     */
    public ?string $template_title = null;

    /**
     * ID направлений.
     *
     * @var int[]
     */
    public ?array $directions = null;

    /**
     * ID профессий.
     *
     * @var int[]
     */
    public ?array $professions = null;

    /**
     * Метод запуска логики.
     *
     * @return CategoryEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws TemplateException
     */
    public function run(): CategoryEntity
    {
        $action = app(CategoryGetAction::class);
        $action->id = $this->id;
        $categoryEntity = $action->run();

        if ($categoryEntity) {
            $templateValues = [];

            $template = new Template();

            $action = app(MetatagSetAction::class);
            $action->description = $template->convert($this->template_description, $templateValues);
            $action->title = $template->convert($this->template_title, $templateValues);
            $action->template_description = $this->template_description;
            $action->template_title = $this->template_title;
            $action->keywords = $this->keywords;
            $action->id = $categoryEntity->metatag_id ?: null;

            $categoryEntity->metatag_id = $action->run()->id;
            $categoryEntity->id = $this->id;
            $categoryEntity->name = $this->name;
            $categoryEntity->header = $template->convert($this->header_template, $templateValues);
            $categoryEntity->link = $this->link;
            $categoryEntity->text = $this->text;
            $categoryEntity->status = $this->status;

            $category = Category::find($this->id);
            $category->update($categoryEntity->toArray());

            $category->directions()->sync($this->directions);
            $category->professions()->sync($this->professions);
            Cache::tags(['catalog', 'category', 'direction', 'profession'])->flush();

            $action = app(CategoryGetAction::class);
            $action->id = $this->id;

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('category::actions.admin.categoryUpdateAction.notExistCategory')
        );
    }
}
