<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Json\Sources;

use Storage;
use App\Modules\Course\Json\Jobs\CategoryItemLinkJob;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Json\Source;
use App\Modules\Category\Models\Category;
use Illuminate\Database\Eloquent\Builder;

/**
 * Источник для формирования категорий.
 */
class CategorySource extends Source
{
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
     * Запуск экспорта данных.
     *
     * @return void.
     */
    public function export(): void
    {
        $count = $this->count();

        for ($i = 0; $i <= $count; $i++) {
            $result = $this->getQuery()
                ->limit(1)
                ->offset($i)
                ->first()
                ?->toArray();

            if ($result) {
                CategoryItemLinkJob::dispatch('/json/categories/' . $result['link'] . '.json', $result['id'], $result['link'])
                    ->delay(now()->addMinute());

                $this->fireEvent('export');
            }
        }
    }

    /**
     * Запуск удаления не активных данных.
     *
     * @return void.
     */
    public function delete(): void
    {
        $activeIds = $this->getQuery()
            ->get()
            ->pluck('id');

        $categories = Category::whereNotIn('id', $activeIds)
            ->get()
            ?->toArray();

        $paths = [];

        foreach ($categories as $category) {
            $path = '/json/categories/' . $category['link'] . '.json';

            if (Storage::drive('public')->exists($path)) {
                $paths[] = $path;
            }
        }

        Storage::drive('public')->delete($paths);
    }

    /**
     * Запрос для получения данных.
     *
     * @return Builder Запрос.
     */
    private function getQuery(): Builder
    {
        return Category::whereHas('courses', function ($query) {
            $query->where('status', Status::ACTIVE->value)
                ->whereHas('school', function ($query) {
                    $query->active()
                        ->hasCourses();
                });
        })
            ->where('status', true)
            ->orderBy('id');
    }
}
