<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Actions\Admin;

use App\Modules\Metatag\Data\MetatagSet;
use Cache;
use Typography;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Category\Entities\Category as CategoryEntity;
use App\Modules\Category\Models\Category;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;
use App\Modules\Category\Data\CategoryUpdate;

/**
 * Класс действия для обновления категорий.
 */
class CategoryUpdateAction extends Action
{
    /**
     * Данные для действия обновления категории.
     *
     * @var CategoryUpdate
     */
    private CategoryUpdate $data;

    /**
     * @param CategoryUpdate $data Данные для действия обновления категории.
     */
    public function __construct(CategoryUpdate $data)
    {
        $this->data = $data;
    }

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
        $action = new CategoryGetAction($this->data->id);
        $categoryEntity = $action->run();

        if ($categoryEntity) {
            $countCategoryCourses = Course::where('courses.status', Status::ACTIVE->value)
                ->whereHas('school', function ($query) {
                    $query->where('schools.status', true);
                })
                ->whereHas('categories', function ($query) {
                    $query->where('categories.id', $this->data->id);
                })
                ->count();

            $templateValues = [
                'category' => $this->data->name,
                'countCategoryCourses' => $countCategoryCourses,
            ];

            $template = new Template();

            $action = new MetatagSetAction(MetatagSet::from([
                'description' => $template->convert($this->data->description_template, $templateValues),
                'title' => $template->convert($this->data->title_template, $templateValues),
                'description_template' => $this->data->description_template,
                'title_template' => $this->data->title_template,
                'keywords' => $this->data->keywords,
                'id' => $categoryEntity->metatag_id ?: null,
            ]));

            $categoryEntity->metatag_id = $action->run()->id;
            $categoryEntity->id = $this->data->id;
            $categoryEntity->name = Typography::process($this->data->name, true);
            $categoryEntity->header = Typography::process($template->convert($this->data->header_template, $templateValues), true);
            $categoryEntity->header_template = $this->data->header_template;
            $categoryEntity->link = $this->data->link;
            $categoryEntity->text = Typography::process($this->data->text);
            $categoryEntity->status = $this->data->status;

            $category = Category::find($this->data->id);
            $category->update($categoryEntity->toArray());

            $category->directions()->sync($this->data->directions);
            $category->professions()->sync($this->data->professions);
            Cache::tags(['catalog', 'category', 'direction', 'profession'])->flush();

            $action = new AnalyzerUpdateAction($categoryEntity->id, Category::class, 'category.text');
            $action->run();

            $action = new CategoryGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('category::actions.admin.categoryUpdateAction.notExistCategory')
        );
    }
}
