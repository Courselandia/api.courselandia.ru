<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Entities;

use App\Models\Entity;

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
     * @var Entity|null
     */
    public ?Entity $description = null;

    /**
     * Количество.
     *
     * @var int|null
     */
    public ?int $total = null;
}
