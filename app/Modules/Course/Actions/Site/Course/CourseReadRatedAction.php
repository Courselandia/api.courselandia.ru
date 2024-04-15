<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Site\Course;

use App\Models\Action;
use App\Modules\Course\Data\Decorators\CourseRead;
use App\Modules\Course\Entities\Course;
use App\Modules\Course\Pipes\Site\Read\ReadPipe;
use App\Modules\Course\Pipes\Site\Read\DescriptionPipe;
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
    private ?int $limit;

    /**
     * @param int|null $limit Лимит выборки.
     */
    public function __construct(?int $limit = null)
    {
        $this->limit = $limit;
    }

    /**
     * Метод запуска логики.
     *
     * @return array<int, Course> Вернет результаты исполнения.
     */
    public function run(): array
    {
        $decorator = new CourseReadDecorator(CourseRead::from([
            'sorts' => [
                'rating' => 'DESC',
            ],
            'filters' => [
                'price' => [70000, 300000],
            ],
            'offset' => 0,
            'limit' => $this->limit,
            'onlyWithImage' => true,
        ]));

        $result = $decorator->setActions([
            ReadPipe::class,
            DescriptionPipe::class,
        ])->run();

        return $result->courses;
    }
}
