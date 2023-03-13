<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Decorators\Site;

use App\Models\Decorator;
use App\Modules\Course\Entities\CourseRead;
use Illuminate\Pipeline\Pipeline;

/**
 * Класс декоратор для чтения курсов.
 */
class CourseReadDecorator extends Decorator
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
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return CourseRead Вернет данные авторизации.
     */
    public function run(): CourseRead
    {
        $courseRead = new CourseRead();
        $courseRead->sorts = $this->sorts;
        $courseRead->filters = $this->filters;
        $courseRead->offset = $this->offset;
        $courseRead->limit = $this->limit;
        $courseRead->section = $this->section;
        $courseRead->sectionLink = $this->sectionLink;
        $courseRead->openedSchools = $this->openedSchools;
        $courseRead->openedCategories = $this->openedCategories;
        $courseRead->openedProfessions = $this->openedProfessions;
        $courseRead->openedTeachers = $this->openedTeachers;
        $courseRead->openedSkills = $this->openedSkills;
        $courseRead->openedTools = $this->openedTools;

        return app(Pipeline::class)
            ->send($courseRead)
            ->through($this->getActions())
            ->thenReturn();
    }
}
