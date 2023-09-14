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
use App\Modules\Direction\Entities\Direction;
use App\Modules\Profession\Entities\Profession;
use App\Modules\School\Entities\School;
use App\Modules\Skill\Entities\Skill;
use App\Modules\Teacher\Entities\Teacher;
use App\Modules\Tool\Entities\Tool;

/**
 * Сущность чтения курсов.
 */
class CourseRead extends Entity
{
    /**
     * Сортировка данных.
     *
     * @var array|null
     */
    public ?array $sorts = null;

    /**
     * Фильтрация данных.
     *
     * @var array|null
     */
    public ?array $filters = null;

    /**
     * Начать выборку.
     *
     * @var int|null
     */
    public ?int $offset = null;

    /**
     * Лимит выборки.
     *
     * @var int|null
     */
    public ?int $limit = null;

    /**
     * Курсы.
     *
     * @var Course[]
     */
    public ?array $courses = null;

    /**
     * Сущность фильтров.
     *
     * @var CourseFilter|null
     */
    public ?CourseFilter $filter = null;

    /**
     * Название описания.
     *
     * @var string|null
     */
    public ?string $section = null;

    /**
     * Ссылка на раздел описания.
     *
     * @var string|null
     */
    public ?string $sectionLink = null;

    /**
     * Сущность описания.
     *
     * @var Direction|Profession|School|Skill|Teacher|Tool|Category|null
     */
    public Direction|Profession|School|Skill|Teacher|Tool|Category|null $description = null;

    /**
     * Количество.
     *
     * @var int|null
     */
    public ?int $total = null;

    /**
     * Отключать не активные.
     *
     * @var bool
     */
    public bool $disabled = false;

    /**
     * Признак школы открыты.
     *
     * @var bool
     */
    public bool $openedSchools = false;

    /**
     * Признак категории открыты.
     *
     * @var bool
     */
    public bool $openedCategories = false;

    /**
     * Признак профессии открыты.
     *
     * @var bool
     */
    public bool $openedProfessions = false;

    /**
     * Признак учителя открыты.
     *
     * @var bool
     */
    public bool $openedTeachers = false;

    /**
     * Признак навыки открыты.
     *
     * @var bool
     */
    public bool $openedSkills = false;

    /**
     * Признак инструменты открыты.
     *
     * @var bool
     */
    public bool $openedTools = false;

    /**
     * Вывести курсы только с картинками.
     *
     * @var bool
     */
    public bool $onlyWithImage = false;
}
