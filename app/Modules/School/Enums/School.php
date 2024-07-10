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
     * Международная Школа Профессий.
     */
    case INTERNATIONAL_SCHOOL_PROFESSIONS = 9;

    /**
     * Eduson Academy
     */
    case EDUSON_ACADEMY = 10;

    /**
     * Coddy
     */
    case CODDY = 11;

    /**
     * Otus
     */
    case OTUS = 12;

    /**
     * Хекслет
     */
    case HEXLET = 13;

    /**
     * Bang Bang Education
     */
    case BANG_BANG_EDUCATION = 14;

    /**
     * Interra
     */
    case INTERRA = 15;

    /**
     * MAED
     */
    case MAED = 16;

    /**
     * АНО «НИИДПО»
     */
    case ANO_NIIDPO = 17;

    /**
     * НАДПО
     */
    case NADPO = 18;

    /**
     * ProductStar
     */
    case PRODUCTSTAR = 19;

    /**
     * Pentaschool
     */
    case PENTASCHOOL = 20;

    /**
     * Бруноям
     */
    case BRUNOYAM = 21;

    /**
     * Логомашина
     */
    case LOGOMASHINA = 22;

    /**
     * Среда обучения
     */
    case SREDA_OBUCHENIA = 23;

    /**
     * SF Education
     */
    case SF_EDUCATION = 24;

    /**
     * Компьютерная Академия TOP
     */
    case TOP_ACADEMY = 25;

    /**
     * Convert Monster
     */
    case CONVERT_MONSTER = 26;

    /**
     * Moscow Digital School
     */
    case MOSCOW_DIGITAL_SCHOOL = 27;

    /**
     * KARPOV.COURSES
     */
    case KARPOV_COURSES = 28;

    /**
     * Слёрм
     */
    case SLERM = 29;

    /**
     * Фоксфорд
     */
    case FOXFORD = 30;

    /**
     * Вебиум
     */
    case VEBIUM = 31;

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
            self::INTERNATIONAL_SCHOOL_PROFESSIONS => 'Международная Школа Профессий',
            self::EDUSON_ACADEMY => 'Eduson Academy',
            self::CODDY => 'Coddy',
            self::OTUS => 'Otus',
            self::HEXLET => 'Хекслет',
            self::BANG_BANG_EDUCATION => 'Bang Bang Education',
            self::INTERRA => 'Interra',
            self::MAED => 'MAED',
            self::ANO_NIIDPO => 'АНО «НИИДПО»',
            self::NADPO => 'НАДПО',
            self::PRODUCTSTAR => 'ProductStar',
            self::PENTASCHOOL => 'Pentaschool',
            self::BRUNOYAM => 'Бруноям',
            self::LOGOMASHINA => 'Логомашина',
            self::SREDA_OBUCHENIA => 'Среда обучения',
            self::SF_EDUCATION => 'SF Education',
            self::TOP_ACADEMY => 'Компьютерная Академия TOP',
            self::CONVERT_MONSTER => 'Convert Monster',
            self::MOSCOW_DIGITAL_SCHOOL => 'Moscow Digital School',
            self::KARPOV_COURSES => 'KARPOV.COURSES',
            self::SLERM => 'Слёрм',
            self::FOXFORD => 'Фоксфорд',
            self::VEBIUM => 'Вебиум',
        };
    }
}
