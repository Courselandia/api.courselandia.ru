<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Site\Course;

use App\Models\Action;
use App\Modules\Course\Decorators\Site\CourseGetDecorator;
use App\Modules\Course\Entities\CourseGet;
use App\Modules\Course\Pipes\Site\Get\GetPipe;
use App\Modules\Course\Entities\Course as CourseEntity;
use App\Modules\Course\Pipes\Site\Get\DataPipe;
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
    public string|null $school = null;

    /**
     * Ссылка курса.
     *
     * @var string|null
     */
    public string|null $link = null;

    /**
     * Метод запуска логики.
     *
     * @return CourseEntity|null Вернет результаты исполнения.
     */
    public function run(): ?CourseGet
    {
        $decorator = app(CourseGetDecorator::class);
        $decorator->school = $this->school;
        $decorator->link = $this->link;

        return $decorator->setActions([
            GetPipe::class,
            SimilaritiesPipe::class,
            DataPipe::class,
        ])->run();
    }
}
