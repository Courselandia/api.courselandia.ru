<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Data\Decorators;

use stdClass;
use App\Models\Entity;
use App\Modules\Course\Entities\Course;
use App\Modules\Course\Entities\CourseFilter;

/**
 * Данные для декоратора для чтения курсов.
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
     * @var ?array<int, Course>
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
     * @var mixed
     */
    public mixed $description = null;

    /**
     * Количество.
     *
     * @var int|null
     */
    public ?int $total = null;

    /**
     * Отключать не активные.
     *
     * @var bool|null
     */
    public bool|null $disabled = null;

    /**
     * Признак школы открыты.
     *
     * @var bool|null
     */
    public bool|null $openedSchools = null;

    /**
     * Признак категории открыты.
     *
     * @var bool|null
     */
    public bool|null $openedCategories = null;

    /**
     * Признак профессии открыты.
     *
     * @var bool|null
     */
    public bool|null $openedProfessions = null;

    /**
     * Признак учителя открыты.
     *
     * @var bool|null
     */
    public bool|null $openedTeachers = null;

    /**
     * Признак навыки открыты.
     *
     * @var bool|null
     */
    public bool|null $openedSkills = null;

    /**
     * Признак инструменты открыты.
     *
     * @var bool|null
     */
    public bool|null $openedTools = null;

    /**
     * Вывести курсы только с картинками.
     *
     * @var ?bool
     */
    public bool|null $onlyWithImage = null;

    /**
     * Признак того, что нам нужно получить только количество курсов.
     *
     * @var bool
     */
    public bool $onlyCount = false;

    /**
     * @param array|null $sorts Сортировка данных.
     * @param array|null $filters Фильтрация данных.
     * @param int|null $offset Начать выборку.
     * @param int|null $limit Лимит выборки.
     * @param array<int, Course>|null $courses Курсы.
     * @param CourseFilter|null $filter Сущность фильтров.
     * @param string|null $section Название описания.
     * @param string|null $sectionLink Ссылка на раздел описания.
     * @param stdClass|null $description Сущность описания.
     * @param int|null $total Количество.
     * @param bool|null $disabled Отключать не активные.
     * @param bool|null $openedSchools Признак школы открыты.
     * @param bool|null $openedCategories Признак категории открыты.
     * @param bool|null $openedProfessions Признак профессии открыты.
     * @param bool|null $openedTeachers Признак учителя открыты.
     * @param bool|null $openedSkills Признак навыки открыты.
     * @param bool|null $openedTools Признак инструменты открыты.
     * @param bool|null $onlyWithImage Вывести курсы только с картинками.
     * @param bool $onlyCount Признак того, что нам нужно получить только количество курсов.
     */
    public function __construct(
        ?array        $sorts = null,
        ?array        $filters = null,
        ?int          $offset = null,
        ?int          $limit = null,
        ?array        $courses = null,
        ?CourseFilter $filter = null,
        ?string       $section = null,
        ?string       $sectionLink = null,
        stdClass|null $description = null,
        ?int          $total = null,
        ?bool         $disabled = null,
        ?bool         $openedSchools = null,
        ?bool         $openedCategories = null,
        ?bool         $openedProfessions = null,
        ?bool         $openedTeachers = null,
        ?bool         $openedSkills = null,
        ?bool         $openedTools = null,
        ?bool         $onlyWithImage = null,
        bool          $onlyCount = false,
    )
    {
        $this->sorts = $sorts;
        $this->filters = $filters;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->courses = $courses;
        $this->filter = $filter;
        $this->section = $section;
        $this->sectionLink = $sectionLink;
        $this->description = $description;
        $this->total = $total;
        $this->disabled = $disabled;
        $this->openedSchools = $openedSchools;
        $this->openedCategories = $openedCategories;
        $this->openedProfessions = $openedProfessions;
        $this->openedTeachers = $openedTeachers;
        $this->openedSkills = $openedSkills;
        $this->openedTools = $openedTools;
        $this->onlyWithImage = $onlyWithImage;
        $this->onlyCount = $onlyCount;
    }
}
