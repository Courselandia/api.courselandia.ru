<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Site\Course;

use App\Models\Action;
use App\Modules\Course\Entities\CourseRead;
use App\Modules\Course\Pipes\Site\Read\ReadPipe;
use App\Modules\Course\Pipes\Site\Read\DescriptionPipe;
use App\Modules\Course\Pipes\Site\Rated\DataPipe;
use App\Modules\Course\Decorators\Site\CourseReadDecorator;

/**
 * Класс действия для получения лучших курсов.
 */
class CourseReadRatedAction extends Action
{
    /**
     * Лимит выборки.
     *
     * @var int|null
     */
    public ?int $limit = null;

    /**
     * Метод запуска логики.
     *
     * @return CourseRead|null Вернет результаты исполнения.
     */
    public function run(): ?CourseRead
    {
        $decorator = app(CourseReadDecorator::class);

        $decorator->sorts = [
            'rating' => 'DESC',
        ];

        $decorator->filters = [
            'price' => [70000, 300000],
        ];

        $decorator->offset = 0;
        $decorator->limit = $this->limit;

        return $decorator->setActions([
            ReadPipe::class,
            DescriptionPipe::class,
            DataPipe::class,
        ])->run();
    }
}
