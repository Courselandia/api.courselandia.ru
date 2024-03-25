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
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Collection\Entities\Collection as CollectionEntity;
use App\Modules\Collection\Models\Collection;
use App\Modules\Analyzer\Actions\Admin\AnalyzerUpdateAction;
use App\Modules\Metatag\Data\MetatagSet;
use App\Modules\Collection\Data\CollectionCreate;
use App\Modules\Collection\Data\CollectionFilterCreate;
use App\Modules\Collection\Entities\CollectionFilter as CollectionFilterEntity;
use App\Modules\Collection\Models\CollectionFilter as CollectionFilterModel;

/**
 * Класс действия для создания колекции.
 */
class CollectionCreateAction extends Action
{
    /**
     * Данные для создания коллекции.
     *
     * @var CollectionCreate
     */
    private CollectionCreate $data;

    /**
     * @param CollectionCreate $data Данные для создания коллекции.
     */
    public function __construct(CollectionCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return CollectionEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException
     * @throws TemplateException
     * @throws Throwable
     */
    public function run(): CollectionEntity
    {
        $id = DB::transaction(function () {
            $action = new MetatagSetAction(MetatagSet::from([
                'description' => $this->data->description,
                'title' => $this->data->title,
                'keywords' => $this->data->keywords,
            ]));

            $metatag = $action->run();

            $collectionEntity = CollectionEntity::from([
                ...$this->data
                    ->except('filters')
                    ->toArray(),
                'name' => Typography::process($this->data->name, true),
                'text' => Typography::process($this->data->text),
                'additional' => Typography::process($this->data->additional),
                'metatag_id' => $metatag->id,
            ]);

            $collectionEntity = $collectionEntity->toArray();

            $collectionEntity['image_small_id'] = $this->data->image;
            $collectionEntity['image_middle_id'] = $this->data->image;
            $collectionEntity['image_big_id'] = $this->data->image;

            $collection = Collection::create($collectionEntity);

            if ($this->data->filters) {
                foreach ($this->data->filters as $filter) {
                    /**
                     * @var CollectionFilterCreate $filter
                     */
                    $entity = CollectionFilterEntity::from([
                        ...$filter->toArray(),
                        'collection_id' => $collection->id,
                    ]);

                    CollectionFilterModel::create($entity->toArray());
                }
            }

            Cache::tags(['catalog', 'collection'])->flush();

            $action = new AnalyzerUpdateAction($collection->id, Collection::class, 'collection.text');
            $action->run();

            return $collection->id;
        });

        $action = new CollectionGetAction($id);

        return $action->run();
    }
}
