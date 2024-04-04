<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Actions\Admin\Collection;

use App\Modules\Collection\Data\CollectionFilter;
use Cache;
use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Collection\Data\CollectionCoursesByFilters;
use App\Modules\Collection\Models\Collection;

/**
 * Класс действия для синхронизации курсов коллекции.
 */
class CollectionCoursesSyncAction extends Action
{
    /**
     * ID коллекции.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * @param int|string $id ID коллекции.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет успешность выполнения операции.
     * @throws RecordNotExistException
     */
    public function run(): bool
    {
        $action = new CollectionGetAction($this->id);
        $collection = $action->run();

        if ($collection) {
            $data = CollectionCoursesByFilters::from([
                'limit' => $collection->amount,
                'sorts' => [
                    $collection->sort_field => $collection->sort_direction,
                ],
                'filters' => $collection->filters,
            ]);

            $data->filters[] = CollectionFilter::from([
                'name' => 'directions-id',
                'value' => json_encode($collection->direction_id),
            ]);

            $action = new CollectionCoursesByFiltersAction($data);
            $courses = $action->run();

            $ids = collect($courses)
                ->pluck('id')
                ->toArray();

            $collection = Collection::find($this->id);
            $collection->courses()->sync($ids);

            Cache::tags(['catalog', 'collection'])->flush();

            return true;
        } else {
            throw new RecordNotExistException(
                trans('collection::actions.admin.collectionCoursesSyncAction.notExistCollection')
            );
        }
    }
}
