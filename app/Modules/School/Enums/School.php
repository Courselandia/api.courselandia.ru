<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Enums;

use App\Models\Enums\EnumLabel;

enum School: int implements EnumLabel
{
    /**
     * Нетология.
     */
    case NETOLOGIA = 1;

    /**
     * Skillbox.
     */
    case SKILLBOX = 2;

    /**
     * GeekBrains.
     */
    case GEEKBRAINS = 3;

    /**
     * SkyPro.
     */
    case SKYPRO = 4;

    /**
     * SkillFactory.
     */
    case SKILL_FACTORY = 5;

    /**
     * Contented.
     */
    case CONTENTED = 6;

    /**
     * XYZ School.
     */
    case XYZ_SCHOOL = 7;

    /**
     * Skillbox Английский (Kespa).
     */
    case SKILLBOX_ENG_KESPA = 8;

    /**
     * Получение лейбл перечисления.
     *
     * @return string|int Вернет лейбл перечисления.
     */
    public function getLabel(): string|int
    {
        return match ($this) {
            self::NETOLOGIA => 'Нетология',
            self::SKILLBOX => 'Skillbox',
            self::GEEKBRAINS => 'GeekBrains',
            self::SKYPRO => 'Skypro',
            self::SKILL_FACTORY => 'SkillFactory',
            self::CONTENTED => 'Contented',
            self::XYZ_SCHOOL => 'XYZ School',
            self::SKILLBOX_ENG_KESPA => 'Skillbox Английский (Kespa)',
        };
    }
}
