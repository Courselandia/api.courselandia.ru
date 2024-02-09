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
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\DataCollection;

/**
 * Сущность фильтров курсов для чтения.
 */
class CourseFilter extends Entity
{
    /**
     * Направления.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(CourseItemFilter::class)]
    public ?DataCollection $directions = null;

    /**
     * Категории.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(CourseItemFilter::class)]
    public ?DataCollection $categories = null;

    /**
     * Профессии.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(CourseItemFilter::class)]
    public ?DataCollection $professions = null;

    /**
     * Школы.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(CourseItemFilter::class)]
    public ?DataCollection $schools = null;

    /**
     * Инструменты.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(CourseItemFilter::class)]
    public ?DataCollection $tools = null;

    /**
     * КАк проходит обучение.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(CourseItemFilter::class)]
    public ?DataCollection $processes = null;

    /**
     * Навыки.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(CourseItemFilter::class)]
    public ?DataCollection $skills = null;

    /**
     * Учителя.
     *
     * @var ?DataCollection
     */
    #[DataCollectionOf(CourseItemFilter::class)]
    public ?DataCollection $teachers = null;

    /**
     * Массив доступных рейтингов.
     *
     * @var int[]|null
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
     * @param DataCollection|null $directions Направления.
     * @param DataCollection|null $categories Категории.
     * @param DataCollection|null $professions Профессии.
     * @param DataCollection|null $schools Школы.
     * @param DataCollection|null $tools Инструменты.
     * @param DataCollection|null $processes Как проходит обучение.
     * @param DataCollection|null $skills Навыки.
     * @param DataCollection|null $teachers Учителя.
     * @param array|null $ratings Массив доступных рейтингов.
     * @param CourseFilterPrice|null $price Цена от и до
     * @param CourseFilterDuration|null $duration Продолжительность от и до
     * @param bool|null $credit Признак наличия фильтра возможности взять кредит.
     * @param bool|null $free Признак наличия фильтра возможности взять бесплатный курс.
     * @param array|null $formats Доступные форматы обучения.
     * @param array|null $levels Доступные уровни.
     */
    public function __construct(
        ?DataCollection       $directions = null,
        ?DataCollection       $categories = null,
        ?DataCollection       $professions = null,
        ?DataCollection       $schools = null,
        ?DataCollection       $tools = null,
        ?DataCollection       $processes = null,
        ?DataCollection       $skills = null,
        ?DataCollection       $teachers = null,
        ?array                $ratings = null,
        ?CourseFilterPrice    $price = null,
        ?CourseFilterDuration $duration = null,
        ?bool                 $credit = null,
        ?bool                 $free = null,
        ?array                $formats = null,
        ?array                $levels = null,
    )
    {
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
