<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Imports;

use App\Modules\Course\Entities\ParserCourse;
use App\Modules\Direction\Enums\Direction;
use App\Modules\School\Enums\School;
use Generator;
use App\Models\Error;

/**
 * Абстрактный класс парсинга курсов.
 */
abstract class Parser
{
    use Error;

    /**
     * Абстрактный класс для получения курса.
     *
     * @return Generator<ParserCourse> Вернет один считанный курс. Если вернет false, то остановка считывания.
     */
    abstract public function read(): Generator;

    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    abstract public function getSchool(): School;

    /**
     * Вернет источник.
     *
     * @return string URL источника.
     */
    abstract public function getSource(): string;

    /**
     * Вернет массив сопоставлений между названиями категорий из источника и их направлениями в системе.
     *
     * @return array<string, Direction> Массив, где ключ это название категории из источника, а значение, это направление в системе.
     * @example ['Онлайн Открытое занятие HR' => Direction::OTHER, 'Онлайн Открытое занятие Программирование' => Direction::PROGRAMMING]
     */
    abstract public function getDirections(): array;
}
