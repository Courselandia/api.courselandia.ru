<?php
/**
 * Модуль Публикации.
 * Этот модуль содержит все классы для работы с публикациями.
 *
 * @package App\Modules\Publication
 */

namespace App\Modules\Publication\Actions\Admin\Publication;

use Typography;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Publication\Entities\Publication as PublicationEntity;
use App\Modules\Publication\Models\Publication;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;

/**
 * Класс действия для создания публикации.
 */
class PublicationCreateAction extends Action
{
    /**
     * Дата добавления.
     *
     * @var ?Carbon
     */
    public ?Carbon $published_at = null;

    /**
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $header = null;

    /**
     * Ссылка.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * Анонс.
     *
     * @var string|null
     */
    public ?string $anons = null;

    /**
     * Статья.
     *
     * @var string|null
     */
    public ?string $article = null;

    /**
     * Изображение.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Описание.
     *
     * @var string|null
     */
    public ?string $description = null;

    /**
     * Ключевые слова.
     *
     * @var string|null
     */
    public ?string $keywords = null;

    /**
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $title = null;

    /**
     * Метод запуска логики.
     *
     * @return PublicationEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): PublicationEntity
    {
        $action = app(MetatagSetAction::class);
        $action->description = $this->description;
        $action->keywords = $this->keywords;
        $action->title = $this->title;
        $metatag = $action->run();

        $publicationEntity = new PublicationEntity();
        $publicationEntity->published_at = $this->published_at;
        $publicationEntity->header = Typography::process($this->header, true);
        $publicationEntity->link = $this->link;
        $publicationEntity->anons = Typography::process($this->anons, true);
        $publicationEntity->article = Typography::process($this->article);
        $publicationEntity->image_small_id = $this->image;
        $publicationEntity->image_middle_id = $this->image;
        $publicationEntity->image_big_id = $this->image;
        $publicationEntity->status = $this->status;
        $publicationEntity->metatag_id = $metatag->id;

        $publication = Publication::create($publicationEntity->toArray());
        Cache::tags(['publication'])->flush();

        $action = app(PublicationGetAction::class);
        $action->id = $publication->id;

        return $action->run();
    }
}
