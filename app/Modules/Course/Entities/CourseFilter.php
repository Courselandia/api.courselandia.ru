<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Entities;

use App\Models\Entity;
use App\Modules\Category\Entities\Category;
use App\Modules\Course\Enums\Format;
use App\Modules\Direction\Entities\Direction;
use App\Modules\Profession\Entities\Profession;
use App\Modules\Salary\Enums\Level;
use App\Modules\School\Entities\School;
use App\Modules\Skill\Entities\Skill;
use App\Modules\Teacher\Entities\Teacher;
use App\Modules\Tool\Entities\Tool;
use App\Modules\Process\Entities\Process;

/**
 * Сущность фильтров курсов для чтения.
 */
class CourseFilter extends Entity
{
    /**
     * Направления.
     *
     * @var Direction[]
     */
    public ?array $directions = null;

    /**
     * Категории.
     *
     * @var Category[]
     */
    public ?array $categories = null;

    /**
     * Профессии.
     *
     * @var Profession[]
     */
    public ?array $professions = null;

    /**
     * Школы.
     *
     * @var School[]
     */
    public ?array $schools = null;

    /**
     * Инструменты.
     *
     * @var Tool[]
     */
    public ?array $tools = null;

    /**
     * КАк проходит обучение.
     *
     * @var Process[]
     */
    public ?array $processes = null;

    /**
     * Навыки.
     *
     * @var Skill[]
     */
    public ?array $skills = null;

    /**
     * Учителя.
     *
     * @var Teacher[]
     */
    public ?array $teachers = null;

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
     * Признак наличия фильтра для курсов онлайн.
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
}
