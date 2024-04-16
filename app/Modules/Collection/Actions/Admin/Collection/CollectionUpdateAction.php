<?php
/**
 * Модуль Коллекций.
 * Этот модуль содержит все классы для работы с коллекциями.
 *
 * @package App\Modules\Collection
 */

namespace App\Modules\Collection\Actions\Admin\Collection;

use DB;
use Cache;
use Throwable;
use Typography;
use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Collection\Entities\Collection as CollectionEntity;
use App\Modules\Collection\Models\Collection;
use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;
use App\Modules\Metatag\Data\MetatagSet;
use App\Modules\Collection\Data\CollectionUpdate;
use App\Modules\Collection\Data\CollectionFilter;
use App\Modules\Collection\Entities\CollectionFilter as CollectionFilterEntity;
use App\Modules\Collection\Models\CollectionFilter as CollectionFilterModel;

/**
 * Класс действия для обновления коллекции.
 */
class CollectionUpdateAction extends Action
{
    /**
     * @var CollectionUpdate Данные для обновления коллекции.
     */
    private CollectionUpdate $data;

    /**
     * @param CollectionUpdate $data Данные для обновления коллекции.
     */
    public function __construct(CollectionUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return CollectionEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws TemplateException|Throwable
     */
    public function run(): CollectionEntity
    {
        $action = new CollectionGetAction($this->data->id);
        $collectionEntity = $action->run();

        if ($collectionEntity) {
            DB::transaction(function () use ($collectionEntity) {
                $action = new MetatagSetAction(MetatagSet::from([
                    'description' => $this->data->description,
                    'title' => $this->data->title,
                    'keywords' => $this->data->keywords,
                    'id' => $collectionEntity->metatag_id ?: null,
                ]));

                $collectionEntity = CollectionEntity::from([
                    ...$collectionEntity->toArray(),
                    ...$this->data
                        ->except('filters')
                        ->toArray(),
                    'metatag_id' => $action->run()->id,
                    'name' => Typography::process($this->data->name, true),
                    'text' => Typography::process($this->data->text),
                    'additional' => Typography::process($this->data->additional),
                ]);

                $collectionEntity = $collectionEntity->toArray();

                if ($this->data->image) {
                    $collectionEntity['image_big_id'] = $this->data->image;
                    $collectionEntity['image_middle_id'] = $this->data->image;
                    $collectionEntity['image_small_id'] = $this->data->image;
                }

                $collection = Collection::find($this->data->id);
                $collection->update($collectionEntity);

                CollectionFilterModel::whereIn('id', collect($collectionEntity['filters'])->pluck('id')->toArray())
                    ->forceDelete();

                if ($this->data->filters) {
                    foreach ($this->data->filters as $filter) {
                        /**
                         * @var CollectionFilter $filter
                         */
                        $entity = CollectionFilterEntity::from([
                            ...$filter->toArray(),
                            'collection_id' => $collection->id,
                        ]);

                        CollectionFilterModel::create($entity->toArray());
                    }
                }

                $action = new CollectionCoursesSyncAction($collection->id);
                $action->run();

                $action = new AnalyzerUpdateAction($this->data->id, Collection::class, 'collection.text');
                $action->run();

                Cache::tags(['catalog', 'collection'])->flush();
            });

            $action =  new CollectionGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('collection::actions.admin.collectionUpdateAction.notExistCollection')
        );
    }
}
