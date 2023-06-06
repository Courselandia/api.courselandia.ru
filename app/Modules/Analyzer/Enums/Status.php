<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Enums;

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
     * Идет проверка текста.
     */
    case PROCESSING = 'processing';

    /**
     * Неуспешный.
     */
    case FAILED = 'failed';
}
