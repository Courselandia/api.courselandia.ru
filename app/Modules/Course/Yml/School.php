<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Yml;

/**
 * Сущность школы.
 */
class School
{
    /**
     * Название школы.
     *
     * @var string
     */
    public string $name;

    /**
     * Название компании.
     *
     * @var string
     */
    public string $company;

    /**
     * Ссылка на сайт.
     *
     * @var string
     */
    public string $url;

    /**
     * Email поддержки.
     *
     * @var string
     */
    public string $email;

    /**
     * Ссылка на логотип.
     *
     * @var string
     */
    public string $picture;

    /**
     * Описание.
     *
     * @var string
     */
    public string $description;
}
