<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Decorators\Site;

use App\Models\Decorator;
use App\Modules\Course\Data\Decorators\CourseRead;
use App\Modules\Course\Entities\CourseRead as CourseReadEntity;
use Illuminate\Pipeline\Pipeline;

/**
 * Класс декоратор для чтения курсов.
 */
class CourseReadDecorator extends Decorator
{
    /**
     * @var CourseRead Данные для декоратора для чтения курсов.
     */
    private CourseRead $data;

    /**
     * @param CourseRead $data Данные для декоратора для чтения курсов.
     */
    public function __construct(CourseRead $data)
    {
        $this->data = $data;
    }

    /**
     * Метод обработчик события после выполнения всех действий декоратора.
     *
     * @return CourseReadEntity|int Вернет сущность считанных курсов либо их количество.
     */
    public function run(): CourseReadEntity|int
    {
        $data = app(Pipeline::class)
            ->send($this->data)
            ->through($this->getActions())
            ->thenReturn();

        if ($this->data->onlyCount) {
            return $data;
        }

        print_r($data->toArray());
        exit;

        /**
         * @var CourseRead $data
         */
        return CourseReadEntity::from($data->toArray());
    }
}
