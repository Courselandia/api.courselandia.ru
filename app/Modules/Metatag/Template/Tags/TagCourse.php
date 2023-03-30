<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Template\Tags;

use Log;
use Throwable;
use Config;
use App\Modules\Metatag\Template\Tag;
use Morpher\Ws3Client\Morpher;

/**
 * Тэг обработчик для названия курса: {course}.
 */
class TagCourse extends Tag
{
    /**
     * Название тэга.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'course';
    }

    /**
     * Конвертирование тэга в значение.
     *
     * @param string|null $value Значения для тэга.
     * @param array<string>|null $configs Настройки тэга.
     * @param array<string, string>|null $values Значения для шаблона.
     *
     * @return string|null
     */
    public function convert(?string $value = null, ?array $configs = null, ?array $values = null): string|null
    {
        try {
            $morpher = new Morpher(Config::get('morph.url'), Config::get('morph.token'));
            $result = $morpher->russian->Parse($value);
            $pad = ucfirst($configs[0]);

            return $result->{$pad};
        } catch (Throwable $error) {
            Log::debug('Morpher Error: ' . $error->getMessage());
        }

        return $value;
    }
}
