<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Actions\Admin\Publication;

use DB;
use App\Models\Action;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Data\MetatagSet;
use App\Modules\Publication\Data\Actions\Admin\PublicationCreate;
use App\Modules\Publication\Entities\Publication as PublicationEntity;
use App\Modules\Publication\Models\Publication;
use Cache;
use Throwable;
use Typography;

/**
 * Класс действия для создания публикации.
 */
class PublicationCreateAction extends Action
{
    /**
     * Данные для создания публикации.
     *
     * @var PublicationCreate
     */
    private PublicationCreate $data;

    /**
     * @param PublicationCreate $data Данные для создания публикации.
     */
    public function __construct(PublicationCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return PublicationEntity Вернет результаты исполнения.
     * @throws Throwable
     */
    public function run(): PublicationEntity
    {
        $id = DB::transaction(function () {
            $action = new MetatagSetAction(MetatagSet::from([
                'description' => $this->data->description,
                'keywords' => $this->data->keywords,
                'title' => $this->data->title,
            ]));

            $metatag = $action->run();

            $publicationEntity = PublicationEntity::from([
                ...$this->data->toArray(),
                'header' => Typography::process($this->data->header, true),
                'anons' => Typography::process($this->data->anons, true, false),
                'article' => Typography::process($this->data->article),
                'metatag_id' => $metatag->id,
            ]);

            $publication = Publication::create([
                ...$publicationEntity->toArray(),
                'image_small_id' => $this->data->image,
                'image_middle_id' => $this->data->image,
                'image_big_id' => $this->data->image,
            ]);

            Cache::tags(['publication'])->flush();

            return $publication->id;
        });

        $action = new PublicationGetAction($id);

        return $action->run();
    }
}
