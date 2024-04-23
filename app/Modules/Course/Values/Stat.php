<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Values;

use App\Models\Value;

/**
 * Объект-значение статистики.
 */
class Stat extends Value
{
    /**
     * Количество курсов.
     *
     * @var int
     */
    private int $amountCourses;

    /**
     * Количество школ.
     *
     * @var int
     */
    private int $amountSchools;

    /**
     * Количество учителей.
     *
     * @var int
     */
    private int $amountTeachers;

    /**
     * Количество отзывов.
     *
     * @var int
     */
    private int $amountReviews;

    /**
     * @param int $amountCourses Количество курсов.
     * @param int $amountSchools Количество школ.
     * @param int $amountTeachers Количество учителей.
     * @param int $amountReviews Количество отзывов.
     */
    public function __construct(int $amountCourses, int $amountSchools, int $amountTeachers, int $amountReviews)
    {
        $this->amountCourses = $amountCourses;
        $this->amountSchools = $amountSchools;
        $this->amountTeachers = $amountTeachers;
        $this->amountReviews = $amountReviews;
    }

    /**
     * Получить количество курсов.
     *
     * @return int Количество курсов.
     */
    public function getAmountCourses(): int
    {
        return $this->amountCourses;
    }

    /**
     * Получить количество курсов.
     *
     * @return int Количество курсов.
     */
    public function getAmountSchools(): int
    {
        return $this->amountSchools;
    }

    /**
     * Получить количество учителей.
     *
     * @return int Количество учителей.
     */
    public function getAmountTeachers(): int
    {
        return $this->amountTeachers;
    }

    /**
     * Получить количество отзывов.
     *
     * @return int Количество отзывов.
     */
    public function getAmountReviews(): int
    {
        return $this->amountReviews;
    }
}