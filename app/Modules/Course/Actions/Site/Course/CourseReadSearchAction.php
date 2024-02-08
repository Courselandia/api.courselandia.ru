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
 * Класс действия для полнотекстового поиска.
 */
class CourseReadSearchAction extends Action
{
    /**
     * Лимит выборки.
     *
     * @var int|null
     */
    private ?int $limit;

    /**
     * Строка поиска.
     *
     * @var string|null
     */
    private ?string $search;

    /**
     * @param int|null $limit Лимит выборки.
     * @param string|null $search Строка поиска.
     */
    public function __construct(
        ?int    $limit = null,
        ?string $search = null,
    )
    {
        $this->limit = $limit;
        $this->search = $search;
    }

    /**
     * Метод запуска логики.
     *
     * @return array Вернет результаты исполнения.
     */
    public function run(): array
    {
        $decorator = new CourseReadDecorator(CourseReadDecoratorData::from([
            'sorts' => [
                'relevance' => 'DESC',
            ],
            'filters' => [
                'search' => $this->search,
            ],
            'offset' => 0,
            'limit' => $this->limit,
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
