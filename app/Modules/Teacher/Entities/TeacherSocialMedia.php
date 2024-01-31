<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Entities;

use App\Models\EntityNew;
use App\Modules\Teacher\Enums\SocialMedia;
use Carbon\Carbon;

/**
 * Сущность для социальных сетей учителя.
 */
class TeacherSocialMedia extends EntityNew
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID учителя.
     *
     * @var int|string|null
     */
    public int|string|null $teacher_id = null;

    /**
     * Название.
     *
     * @var SocialMedia|null
     */
    public ?SocialMedia $name = null;

    /**
     * Значение.
     *
     * @var string|null
     */
    public ?string $value = null;

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
     * @param int|string|null $id ID записи.
     * @param int|string|null $teacher_id ID учителя.
     * @param SocialMedia|null $name Название.
     * @param string|null $value Значение.
     * @param Carbon|null $created_at Дата создания.
     * @param Carbon|null $updated_at Дата обновления.
     * @param Carbon|null $deleted_at Дата удаления.
     */
    public function __construct(
        int|string|null $id = null,
        int|string|null $teacher_id = null,
        ?SocialMedia    $name = null,
        ?string         $value = null,
        ?Carbon         $created_at = null,
        ?Carbon         $updated_at = null,
        ?Carbon         $deleted_at = null
    )
    {
        $this->id = $id;
        $this->teacher_id = $teacher_id;
        $this->name = $name;
        $this->value = $value;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
        $this->deleted_at = $deleted_at;
    }
}
