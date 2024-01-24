<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Actions\Admin;

use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;
use App\Modules\Category\Data\CategoryCreate;
use App\Modules\Metatag\Data\MetatagSet;
use Cache;
use Typography;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Category\Entities\Category as CategoryEntity;
use App\Modules\Category\Models\Category;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;

/**
 * Класс действия для создания категории.
 */
class CategoryCreateAction extends Action
{
    /**
     * Данные для действия создание категории.
     *
     * @var CategoryCreate
     */
    private CategoryCreate $data;

    /**
     * @param CategoryCreate $data Данные для действия создание категории.
     */
    public function __construct(CategoryCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return CategoryEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException
     * @throws TemplateException
     */
    public function run(): CategoryEntity
    {
        $template = new Template();

        $templateValues = [
            'category' => $this->data->name,
            'countCategoryCourses' => 0,
        ];

        $metatagSet = MetatagSet::from([
            'description' => $template->convert($this->data->description_template, $templateValues),
            'title' => $template->convert($this->data->title_template, $templateValues),
            'description_template' => $this->data->description_template,
            'title_template' => $this->data->title_template,
            'keywords' => $this->data->keywords,
        ]);

        $action = new MetatagSetAction($metatagSet);
        $metatag = $action->run();

        $category = Category::create([
            ...$this->data->toArray(),
            'name' => Typography::process($this->data->name, true),
            'header' => Typography::process($template->convert($this->data->header_template, $templateValues), true),
            'text' => Typography::process($this->data->text),
            'metatag_id' => $metatag->id,
        ]);

        $category->directions()->sync($this->data->directions ?: []);
        $category->professions()->sync($this->data->professions ?: []);

        Cache::tags(['catalog', 'category', 'direction', 'profession'])->flush();

        $action = new AnalyzerUpdateAction($category->id, Category::class, 'category.text');
        $action->run();

        $action = new CategoryGetAction($category->id);

        return $action->run();
    }
}
