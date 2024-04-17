<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Actions\Admin;

use DB;
use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;
use App\Modules\Category\Data\CategoryCreate;
use App\Modules\Metatag\Data\MetatagSet;
use Cache;
use Throwable;
use Typography;
use App\Models\Action;
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
     * @throws TemplateException
     * @throws Throwable
     */
    public function run(): CategoryEntity
    {
        $id = DB::transaction(function () {
            $template = new Template();

            $templateValues = [
                'category' => $this->data->name,
                'countCategoryCourses' => 0,
            ];

            $metatagSet = MetatagSet::from([
                'description' => Typography::process($template->convert($this->data->description_template, $templateValues), true),
                'title' => Typography::process($template->convert($this->data->title_template, $templateValues), true),
                'description_template' => $this->data->description_template,
                'title_template' => $this->data->title_template,
                'keywords' => $this->data->keywords,
            ]);

            $action = new MetatagSetAction($metatagSet);
            $metatag = $action->run();

            $categoryEntity = CategoryEntity::from([
                ...$this->data->except('directions', 'professions')->toArray(),
                'name' => Typography::process($this->data->name, true),
                'header' => Typography::process($template->convert($this->data->header_template, $templateValues), true),
                'text' => Typography::process($this->data->text),
                'additional' => Typography::process($this->data->additional),
                'metatag_id' => $metatag->id,
            ]);

            $category = Category::create($categoryEntity->toArray());

            $category->directions()->sync($this->data->directions ?: []);
            $category->professions()->sync($this->data->professions ?: []);

            Cache::tags(['catalog', 'category'])->flush();

            $action = new AnalyzerUpdateAction($category->id, Category::class, 'category.text');
            $action->run();

            return $category->id;
        });

        $action = new CategoryGetAction($id);

        return $action->run();
    }
}
