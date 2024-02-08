<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Decorators\Site;

use App\Models\Decorator;
use App\Modules\Course\Data\Decorators\CourseGet;
use App\Modules\Course\Entities\CourseGet as CourseGetEntity;
use Illuminate\Pipeline\Pipeline;

/**
 * Класс декоратор для получения курса.
 */
class CourseGetDecorator extends Decorator
{
    /**
     * Ссылка школы.
     *
     * @var string|null
     */
    private string|null $school;

    /**
     * Ссылка курса.
     *
     * @var string|null
     */
    private string|null $link;

    /**
     * ID курса.
     *
     * @var string|int|null
     */
    private string|int|null $id;

    /**
     * @param string|null $school Ссылка школы.
     * @param string|null $link Ссылка курса.
     * @param string|int|null $id ID курса.
     */
    public function __construct(
        string|null     $school = null,
        string|null     $link = null,
        string|int|null $id = null,
    )
    {
        $this->school = $school;
        $this->link = $link;
        $this->id = $id;
    }

    /**
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return CourseGetEntity Вернет данные школы.
     */
    public function run(): CourseGetEntity
    {
        $courseRead = new CourseGet();
        $courseRead->school = $this->school;
        $courseRead->link = $this->link;
        $courseRead->id = $this->id;

        $data = app(Pipeline::class)
            ->send($courseRead)
            ->through($this->getActions())
            ->thenReturn();

        /**
         * @var CourseGet $data
         */
        return CourseGetEntity::from($data->toArray());
    }
}
