<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Entities;

use App\Models\Entity;
use App\Modules\Course\Enums\Format;
use App\Modules\Salary\Enums\Level;

/**
 * Сущность фильтров курсов для чтения.
 */
class CourseFilter extends Entity
{
    /**
     * Направления.
     *
     * @var ?array<int, CourseItemFilter>
     */
    public ?array $directions = null;

    /**
     * Категории.
     *
     * @var ?array<int, CourseItemFilter>
     */
    public ?array $categories = null;

    /**
     * Профессии.
     *
     * @var ?array<int, CourseItemFilter>
     */
    public ?array $professions = null;

    /**
     * Школы.
     *
     * @var ?array<int, CourseItemFilter>
     */
    public ?array $schools = null;

    /**
     * Инструменты.
     *
     * @var ?array<int, CourseItemFilter>
     */
    public ?array $tools = null;

    /**
     * КАк проходит обучение.
     *
     * @var ?array<int, CourseItemFilter>
     */
    public ?array $processes = null;

    /**
     * Навыки.
     *
     * @var ?array<int, CourseItemFilter>
     */
    public ?array $skills = null;

    /**
     * Учителя.
     *
     * @var ?array<int, CourseItemFilter>
     */
    public ?array $teachers = null;

    /**
     * Массив доступных рейтингов.
     *
     * @var ?array<int, CourseFilterRating>
     */
    public ?array $ratings = null;

    /**
     * Цена от и до
     *
     * @var CourseFilterPrice|null
     */
    public ?CourseFilterPrice $price = null;

    /**
     * Продолжительность от и до
     *
     * @var CourseFilterDuration|null
     */
    public ?CourseFilterDuration $duration = null;

    /**
     * Признак наличия фильтра возможности взять кредит.
     *
     * @var bool|null
     */
    public ?bool $credit = null;

    /**
     * Признак наличия фильтра возможности взять бесплатный курс.
     *
     * @var bool|null
     */
    public ?bool $free = null;

    /**
     * Доступные форматы обучения.
     *
     * @var Format[]
     */
    public ?array $formats = null;

    /**
     * Доступные уровни.
     *
     * @var Level[]
     */
    public ?array $levels = null;

    /**
     * @param array<int, CourseItemFilter>|null $directions Направления.
     * @param array<int, CourseItemFilter>|null $categories Категории.
     * @param array<int, CourseItemFilter>|null $professions Профессии.
     * @param array<int, CourseItemFilter>|null $schools Школы.
     * @param array<int, CourseItemFilter>|null $tools Инструменты.
     * @param array<int, CourseItemFilter>|null $processes Как проходит обучение.
     * @param array<int, CourseItemFilter>|null $skills Навыки.
     * @param array<int, CourseItemFilter>|null $teachers Учителя.
     * @param array<int, CourseFilterRating>|null $ratings Массив доступных рейтингов.
     * @param CourseFilterPrice|null $price Цена от и до
     * @param CourseFilterDuration|null $duration Продолжительность от и до
     * @param bool|null $credit Признак наличия фильтра возможности взять кредит.
     * @param bool|null $free Признак наличия фильтра возможности взять бесплатный курс.
     * @param array|null $formats Доступные форматы обучения.
     * @param array|null $levels Доступные уровни.
     */
    public function __construct(
        ?array $directions = null,
        ?array $categories = null,
        ?array $professions = null,
        ?array $schools = null,
        ?array $tools = null,
        ?array $processes = null,
        ?array $skills = null,
        ?array $teachers = null,
        ?array $ratings = null,
        ?CourseFilterPrice $price = null,
        ?CourseFilterDuration $duration = null,
        ?bool $credit = null,
        ?bool $free = null,
        ?array $formats = null,
        ?array $levels = null,
    ) {
        $this->directions = $directions;
        $this->categories = $categories;
        $this->professions = $professions;
        $this->schools = $schools;
        $this->tools = $tools;
        $this->processes = $processes;
        $this->skills = $skills;
        $this->teachers = $teachers;
        $this->ratings = $ratings;
        $this->price = $price;
        $this->duration = $duration;
        $this->credit = $credit;
        $this->free = $free;
        $this->formats = $formats;
        $this->levels = $levels;
    }
}
