<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\DbFile\Sources;

use App\Modules\Course\Actions\Site\Course\CourseReadAction;
use Generator;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\DbFile\Item;
use App\Modules\Course\DbFile\Source;
use App\Modules\Direction\Models\Direction;
use Illuminate\Database\Eloquent\Builder;

/**
 * Источник для формирования направлений.
 */
class SourceDirection extends Source
{
    /**
     * Получить путь к папке хранения файлов.
     *
     * @return string Путь к папке.
     */
    public function getPathToDir(): string
    {
        return '/directions';
    }

    /**
     * Общее количество генерируемых данных.
     *
     * @return int Количество данных.
     */
    public function count(): int
    {
        return $this->getQuery()->count();
    }

    /**
     * Чтение данных.
     *
     * @return Generator<Item> Элемент для сохранения.
     */
    public function read(): Generator
    {
        $count = $this->count();

        for ($i = 0; $i <= $count; $i++) {
            $result = $this->getQuery()
                ->limit(1)
                ->offset($i)
                ->first()
                ?->toArray();

            if ($result) {
                $action = app(CourseReadAction::class);
                $action->sorts = ['name' => 'ASC'];
                $action->filters = ['directions-id' => $result['id']];
                $action->offset = 0;
                $action->limit = 36;

                $entityCourseRead = $action->run();

                $item = new Item();
                $item->id = $result['id'];
                $item->data = $entityCourseRead;

                yield $item;
            }
        }
    }

    /**
     * Запрос для получения данных.
     *
     * @return Builder Запрос.
     */
    private function getQuery(): Builder
    {
        return Direction::whereHas('courses', function ($query) {
            $query->where('status', Status::ACTIVE->value)
                ->whereHas('school', function ($query) {
                    $query->where('status', true);
                });
        })->orderBy('id');
    }
}
