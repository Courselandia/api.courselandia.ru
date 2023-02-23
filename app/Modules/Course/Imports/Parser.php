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
     * Источник.
     *
     * @var string
     */
    private string $source;

    /**
     * Конструктор.
     *
     * @param string $source URL источника.
     */
    public function __construct(string $source)
    {
        $this->source = $source;
    }

    /**
     * Абстрактный класс для получения курса.
     *
     * @return Generator<ParserCourse> Вернет один считанный курс.
     */
    abstract public function read(): Generator;

    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    abstract public function getSchool(): School;

    /**
     * Вернет массив сопоставлений между названиями категорий из источника и их направлениями в системе.
     *
     * @return array<string, Direction> Массив, где ключ это название категории из источника, а значение, это направление в системе.
     * @example ['Онлайн Открытое занятие HR' => Direction::OTHER, 'Онлайн Открытое занятие Программирование' => Direction::PROGRAMMING]
     */
    abstract public function getDirections(): array;

    /**
     * Вернет источник.
     *
     * @return string URL источника.
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * Установит источник.
     *
     * @param string $source URL источника.
     *
     * @return $this
     */
    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }
}
