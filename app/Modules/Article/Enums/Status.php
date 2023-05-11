<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Enums;

/**
 * Статус написанной статьи.
 */
enum Status: string
{
    /**
     * В ожидании.
     */
    case PENDING = 'pending';

    /**
     * Готово.
     */
    case READY = 'ready';

    /**
     * Идет написание текста.
     */
    case PROCESSING = 'processing';

    /**
     * Неуспешный.
     */
    case FAILED = 'failed';

    /**
     * Отключенный.
     */
    case DISABLED = 'disabled';
}
