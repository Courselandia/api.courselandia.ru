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
use App\Modules\Category\Entities\Category as CategoryEntity;
use App\Modules\Category\Models\Category;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use Cache;

/**
 * Класс действия для создания категории.
 */
class CategoryCreateAction extends Action
{
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
    public ?string $description_template = null;

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
    public ?string $title_template = null;

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
     * @throws ParameterInvalidException
     * @throws TemplateException
     */
    public function run(): CategoryEntity
    {
        $action = app(MetatagSetAction::class);
        $template = new Template();

        $templateValues = [
            'category' => $this->name,
            'countCategoryCourses' => 0,
        ];

        $action->description = $template->convert($this->description_template, $templateValues);
        $action->title = $template->convert($this->title_template, $templateValues);
        $action->description_template = $this->description_template;
        $action->title_template = $this->title_template;
        $action->keywords = $this->keywords;

        $metatag = $action->run();

        $categoryEntity = new CategoryEntity();
        $categoryEntity->name = $this->name;
        $categoryEntity->header = $template->convert($this->header_template, $templateValues);
        $categoryEntity->header_template = $this->header_template;
        $categoryEntity->link = $this->link;
        $categoryEntity->text = $this->text;
        $categoryEntity->status = $this->status;
        $categoryEntity->metatag_id = $metatag->id;

        $category = Category::create($categoryEntity->toArray());

        $category->directions()->sync($this->directions ?: []);
        $category->professions()->sync($this->professions ?: []);

        Cache::tags(['catalog', 'category', 'direction', 'profession'])->flush();

        $action = app(CategoryGetAction::class);
        $action->id = $category->id;

        return $action->run();
    }
}
