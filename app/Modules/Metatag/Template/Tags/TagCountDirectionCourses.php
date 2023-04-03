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
use Morpher\Ws3Client\Morpher;

/**
 * Тэг обработчик для количества курсов в направлении: {countDirectionCourses}.
 */
class TagCountDirectionCourses extends TagSchool
{
    /**
     * Название тэга.
     *
     * @return string
     */
    public function getName(): string
    {
        return 'countDirectionCourses';
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
            if (isset($configs[0])) {
                $value = (int)$value;
                $morpher = new Morpher(Config::get('morph.url'), Config::get('morph.token'));
                $unit = $morpher->russian->Parse($configs[0]);
                $unit = $value === 1 ? $unit->Nominative : $unit->Plural->Nominative;
                $result = $morpher->russian->Spell((int)$value, $unit);
                $pad = ucfirst($configs[1]);

                return $value . ' ' . $result->UnitDeclension->{$pad};
            }

            return $value;
        } catch (Throwable $error) {
            Log::debug('Morpher Error: ' . $error->getMessage());
        }

        return $value;
    }
}
