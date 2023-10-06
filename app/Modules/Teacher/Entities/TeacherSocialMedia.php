<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Entities;

use App\Models\Entity;
use App\Modules\Teacher\Enums\SocialMedia;
use Carbon\Carbon;

/**
 * Сущность для социальных сетей учителя.
 */
class TeacherSocialMedia extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID чителя.
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
     * Название.
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
}
