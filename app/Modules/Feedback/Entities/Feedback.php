<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Entities;

use App\Models\Entity;
use Carbon\Carbon;

/**
 * Сущность для обратной связи.
 */
class Feedback extends Entity
{
    /**
     * ID записи.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Имя.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * E-mail.
     *
     * @var string|null
     */
    public ?string $email = null;

    /**
     * Телефон.
     *
     * @var string|null
     */
    public ?string $phone = null;

    /**
     * Сообщение.
     *
     * @var string|null
     */
    public ?string $message = null;

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