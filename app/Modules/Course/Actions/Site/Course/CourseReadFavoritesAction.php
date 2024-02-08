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
use App\Modules\Course\Pipes\Site\Rated\DataPipe;
use App\Modules\Course\Decorators\Site\CourseReadDecorator;
use App\Modules\Course\Data\Decorators\CourseRead as CourseReadDecoratorData;

/**
 * Класс действия для получения избранного.
 */
class CourseReadFavoritesAction extends Action
{
    /**
     * IDs избранного.
     *
     * @var int[]
     */
    private array $ids;

    /**
     * @param array $ids IDs избранного.
     */
    public function __construct(array $ids = [])
    {
        $this->ids = $ids;
    }

    /**
     * Метод запуска логики.
     *
     * @return array Вернет результаты исполнения.
     */
    public function run(): array
    {
        $decorator = new CourseReadDecorator(CourseReadDecoratorData::from([
            'filters' => [
                'ids' => $this->ids,
            ],
        ]));

        $result = $decorator->setActions([
            ReadPipe::class,
            DataPipe::class,
        ])->run();

        /**
         * @var CourseRead $result
         */
        return [
            'data' => $result->courses,
            'total' => $result->total,
        ];
    }
}
