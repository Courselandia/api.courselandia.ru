<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Data\Actions;

use App\Models\Data;

/**
 * Данные для действия получения курсов.
 */
class CourseRead extends Data
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
     * Раздел описания.
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
     * @param array|null $sorts Сортировка данных.
     * @param array|null $filters Фильтрация данных.
     * @param int|null $offset Начать выборку.
     * @param int|null $limit Лимит выборки.
     * @param string|null $section Раздел описания.
     * @param string|null $sectionLink Ссылка на раздел описания.
     * @param bool $disabled Отключать не активные.
     * @param bool $openedSchools Признак школы открыты.
     * @param bool $openedCategories Признак категории открыты.
     * @param bool $openedProfessions Признак профессии открыты.
     * @param bool $openedTeachers Признак учителя открыты.
     * @param bool $openedSkills Признак навыки открыты.
     * @param bool $openedTools Признак инструменты открыты.
     */
    public function __construct(
        ?array  $sorts = null,
        ?array  $filters = null,
        ?int    $offset = null,
        ?int    $limit = null,
        ?string $section = null,
        ?string $sectionLink = null,
        bool    $disabled = false,
        bool    $openedSchools = false,
        bool    $openedCategories = false,
        bool    $openedProfessions = false,
        bool    $openedTeachers = false,
        bool    $openedSkills = false,
        bool    $openedTools = false,
    )
    {
        $this->sorts = $sorts;
        $this->filters = $filters;
        $this->offset = $offset;
        $this->limit = $limit;
        $this->section = $section;
        $this->sectionLink = $sectionLink;
        $this->disabled = $disabled;
        $this->openedSchools = $openedSchools;
        $this->openedCategories = $openedCategories;
        $this->openedProfessions = $openedProfessions;
        $this->openedTeachers = $openedTeachers;
        $this->openedSkills = $openedSkills;
        $this->openedTools = $openedTools;
    }
}
