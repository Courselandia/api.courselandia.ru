<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Actions\Admin\Collection;

use App\Models\Action;
use App\Modules\Collection\Data\CollectionFilter;
use App\Modules\Course\Entities\Course;
use App\Modules\Course\Entities\CourseRead;
use App\Modules\Collection\Data\CollectionCoursesByFilters;
use App\Modules\Course\Actions\Site\Course\CourseReadAction;

/**
 * Класс действия для получения курсов по фильтру.
 */
class CollectionCoursesByFiltersAction extends Action
{
    /**
     * Данные для создания получения курсов через фильтр.
     *
     * @var CollectionCoursesByFilters
     */
    private CollectionCoursesByFilters $data;

    /**
     * @param CollectionCoursesByFilters $data Данные для создания получения курсов через фильтр.
     */
    public function __construct(CollectionCoursesByFilters $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return array<int, Course>|int Вернет массив курсов или их количество.
     */
    public function run(): array|int
    {
        $filters = [];

        for ($i = 0; $i < count($this->data->filters); $i++) {
            /**
             * @var CollectionFilter $filter
             */
            $filter = $this->data->filters[$i];
            $filters[$filter->name] = json_decode($filter->value, true);
        }

        $data = CourseRead::from([
            'filters' => $filters,
            'sorts' => $this->data->sorts,
            'limit' => $this->data->limit,
            'onlyCount' => $this->data->onlyCount,
        ]);

        $action = new CourseReadAction($data);
        $data = $action->run();

        if ($this->data->onlyCount) {
            return $data;
        }

        return $data->courses;
    }
}
