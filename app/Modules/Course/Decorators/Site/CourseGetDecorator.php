<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Decorators\Site;

use App\Models\Decorator;
use App\Modules\Course\Entities\CourseGet;
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
    public string|null $school = null;

    /**
     * Ссылка курса.
     *
     * @var string|null
     */
    public string|null $link = null;

    /**
     * ID курса.
     *
     * @var string|int|null
     */
    public string|int|null $id = null;

    /**
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return CourseGet Вернет данные школы.
     */
    public function run(): CourseGet
    {
        $courseRead = new CourseGet();
        $courseRead->school = $this->school;
        $courseRead->link = $this->link;
        $courseRead->id = $this->id;

        return app(Pipeline::class)
            ->send($courseRead)
            ->through($this->getActions())
            ->thenReturn();
    }
}
