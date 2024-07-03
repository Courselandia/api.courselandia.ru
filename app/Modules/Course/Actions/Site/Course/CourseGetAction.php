<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Site\Course;

use App\Models\Action;
use App\Modules\Course\Entities\CourseGet as CourseGetEntity;
use App\Modules\Course\Decorators\Site\CourseGetDecorator;
use App\Modules\Course\Entities\Course as CourseEntity;
use App\Modules\Course\Pipes\Site\Get\DataPipe;
use App\Modules\Course\Pipes\Site\Get\GetPipe;
use App\Modules\Course\Pipes\Site\Get\SimilaritiesPipe;

/**
 * Класс действия для получения курса.
 */
class CourseGetAction extends Action
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
        string|null $school = null,
        string|null $link = null,
        string|int|null $id = null,
    ) {
        $this->school = $school;
        $this->link = $link;
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return CourseEntity|null Вернет результаты исполнения.
     */
    public function run(): ?CourseGetEntity
    {
        $decorator = new CourseGetDecorator($this->school, $this->link, $this->id);

        return $decorator->setActions([
            GetPipe::class,
            SimilaritiesPipe::class,
            DataPipe::class,
        ])->run();
    }
}
