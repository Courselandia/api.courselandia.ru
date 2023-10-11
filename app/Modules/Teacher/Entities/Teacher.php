<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Entities;

use Carbon\Carbon;
use App\Models\Entities;
use App\Models\Entity;
use App\Modules\Direction\Entities\Direction;
use App\Modules\School\Entities\School;
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Entities\Metatag;
use Illuminate\Http\UploadedFile;

/**
 * Сущность для учителя.
 */
class Teacher extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID метатегов.
     *
     * @var int|string|null
     */
    public int|string|null $metatag_id = null;

    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Ссылка.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * Статья.
     *
     * @var string|null
     */
    public ?string $text = null;

    /**
     * Рейтинг.
     *
     * @var float|null
     */
    public ?float $rating = null;

    /**
     * Город.
     *
     * @var string|null
     */
    public ?string $city = null;

    /**
     * Комментарий.
     *
     * @var string|null
     */
    public ?string $comment = null;

    /**
     * Скопировано.
     *
     * @var bool|null
     */
    public ?bool $copied = null;

    /**
     * Изображение маленькое.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_small_id = null;

    /**
     * Изображение среднее.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_middle_id = null;

    /**
     * Изображение большое.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_big_id = null;

    /**
     * Опции порезанного изображения.
     *
     * @var array|null
     */
    public array|null $image_cropped_options = null;

    /**
     * Метатеги.
     *
     * @var Metatag|null
     */
    public ?Metatag $metatag = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Дата создания.
     *
     * @var ?Carbon
     */
    public ?Carbon $created_at = null;

    /**
     * Дата обновления.
     *
     * @var ?Carbon
     */
    public ?Carbon $updated_at = null;

    /**
     * Дата удаления.
     *
     * @var ?Carbon
     */
    public ?Carbon $deleted_at = null;

    /**
     * Направления.
     *
     * @var Direction[]
     */
    #[Entities(Direction::class)]
    public ?array $directions = null;

    /**
     * Направления.
     *
     * @var School[]
     */
    #[Entities(School::class)]
    public ?array $schools = null;

    /**
     * Опыт работы учителя.
     *
     * @var TeacherExperience[]
     */
    #[Entities(TeacherExperience::class)]
    public ?array $experiences = null;

    /**
     * Социальные сети учителя.
     *
     * @var TeacherExperience[]
     */
    #[Entities(TeacherSocialMedia::class)]
    public ?array $social_medias = null;
}
