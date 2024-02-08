<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

use App\Models\Data;
use App\Modules\Course\Enums\Status;
use Util;
use Cache;
use Closure;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Models\Course;
use App\Models\Contracts\Pipe;
use App\Modules\Course\Data\Decorators\CourseRead;

/**
 * Чтение курсов: фильтры: рейтинги.
 */
class FilterRatingPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Data|CourseRead $data Данные для декоратора для чтения курсов.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Data|CourseRead $data, Closure $next): mixed
    {
        $currentFilters = $data->filters;

        if (isset($currentFilters['rating'])) {
            unset($currentFilters['rating']);
        }

        $ratings = [
            [
                'label' => 4.5,
                'disabled' => !$this->hasRating(4.5, $currentFilters),
            ],
            [
                'label' => 4,
                'disabled' => !$this->hasRating(4, $currentFilters),
            ],
            [
                'label' => 3.5,
                'disabled' => !$this->hasRating(3.5, $currentFilters),
            ],
            [
                'label' => 3,
                'disabled' => !$this->hasRating(3, $currentFilters),
            ]
        ];

        $data->filter->ratings = $ratings;

        return $next($data);
    }

    /**
     * Проверка наличия курсов с определенным рейтингом.
     *
     * @param float $rating Проверка наличия курсов с этим рейтингом и выше.
     * @param array|null $filters Массив фильтров.
     *
     * @return bool Вернет признак наличия курсов с указанным рейтингом.
     */
    private function hasRating(float $rating, array $filters = null): bool
    {
        $cacheKey = Util::getKey(
            'course',
            'rating',
            'site',
            'read',
            $rating,
            $filters,
        );

        return Cache::tags([
            'course',
            'direction',
            'profession',
            'category',
            'skill',
            'teacher',
            'tool',
            'processes',
            'employment',
        ])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($rating, $filters) {
                return !!Course::filter($filters ?: [])
                    ->where('rating', '>=', $rating)
                    ->where('status', Status::ACTIVE->value)
                    ->where('has_active_school', true)
                    ->count();
            }
        );
    }
}
