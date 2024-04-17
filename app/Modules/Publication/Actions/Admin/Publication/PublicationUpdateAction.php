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
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Data\MetatagSet;
use App\Modules\Publication\Data\Actions\Admin\PublicationUpdate;
use App\Modules\Publication\Entities\Publication as PublicationEntity;
use App\Modules\Publication\Models\Publication;
use Cache;
use Throwable;
use Typography;

/**
 * Класс действия для обновления публикаций.
 */
class PublicationUpdateAction extends Action
{
    /**
     * Данные для обновления публикации.
     *
     * @var PublicationUpdate
     */
    private PublicationUpdate $data;

    /**
     * @param PublicationUpdate $data Данные для обновления публикации.
     */
    public function __construct(PublicationUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return PublicationEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws Throwable
     */
    public function run(): PublicationEntity
    {
        $action = new PublicationGetAction($this->data->id);
        $publicationEntity = $action->run();

        if ($publicationEntity) {
            DB::transaction(function () use ($publicationEntity) {
                $action = new MetatagSetAction(MetatagSet::from([
                    'title' => Typography::process($this->data->title, true),
                    'description' => Typography::process($this->data->description, true),
                    'keywords' => $this->data->keywords,
                    'id' => $publicationEntity->metatag_id ?: null,
                ]));

                $publicationEntity = PublicationEntity::from([
                    ...$publicationEntity->toArray(),
                    ...$this->data->toArray(),
                    'metatag_id' => $action->run()->id,
                    'header' => Typography::process($this->data->header, true),
                    'anons' => Typography::process($this->data->anons, true, false),
                    'article' => Typography::process($this->data->article),
                ]);

                if ($this->data->image) {
                    Publication::find($this->data->id)->update([
                        ...$publicationEntity->toArray(),
                        'image_small_id' => $this->data->image,
                        'image_middle_id' => $this->data->image,
                        'image_big_id' => $this->data->image,
                    ]);
                } else {
                    Publication::find($this->data->id)->update($publicationEntity->toArray());
                }

                Cache::tags(['publication'])->flush();
            });

            $action = new PublicationGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('publication::actions.admin.publicationUpdateAction.notExistPublication')
        );
    }
}
