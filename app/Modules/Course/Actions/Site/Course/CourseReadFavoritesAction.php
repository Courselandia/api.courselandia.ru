<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Actions\Site\Course;

use App\Models\Action;
use App\Models\Clean;
use App\Modules\Course\Entities\CourseRead;
use App\Modules\Course\Pipes\Site\Read\ReadPipe;
use App\Modules\Course\Pipes\Site\Rated\DataPipe;
use App\Modules\Course\Decorators\Site\CourseReadDecorator;
use JetBrains\PhpStorm\ArrayShape;

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
    public array $ids = [];

    /**
     * Метод запуска логики.
     *
     * @return array Вернет результаты исполнения.
     */
    #[ArrayShape(['data' => 'array', 'total' => 'int'])] public function run(): array
    {
        $decorator = app(CourseReadDecorator::class);

        $decorator->filters = [
            'id' => $this->ids,
        ];

        $result = $decorator->setActions([
            ReadPipe::class,
            DataPipe::class,
        ])->run();

        $result = Clean::do($result, [
            'openedSchools',
            'openedCategories',
            'openedProfessions',
            'openedProfessions',
            'openedTeachers',
            'openedSkills',
            'openedTools',
        ]);

        /**
         * @var CourseRead $result
         */
        return [
            'data' => $result->courses,
            'total' => $result->total,
        ];
    }
}
