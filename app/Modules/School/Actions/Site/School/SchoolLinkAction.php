<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Actions\Site\School;

use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Course\Enums\Status;
use App\Modules\School\Entities\School as SchoolEntity;
use App\Modules\School\Models\School;
use App\Modules\Review\Enums\Status as ReviewStatus;

/**
 * Класс действия для получения школы.
 */
class SchoolLinkAction extends Action
{
    /**
     * Ссылка школы.
     *
     * @var string
     */
    private string $link;

    /**
     * @param string $link Ссылка школы.
     */
    public function __construct(string $link)
    {
        $this->link = $link;
    }

    /**
     * Метод запуска логики.
     *
     * @return SchoolEntity|null Вернет результаты исполнения.
     */
    public function run(): ?SchoolEntity
    {
        $cacheKey = Util::getKey('school', 'site', $this->link, 'metatag');

        return Cache::tags(['catalog', 'school'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $school = School::select([
                    'id',
                    'metatag_id',
                    'name',
                    'header',
                    'header_template',
                    'link',
                    'text',
                    'additional',
                    'rating',
                    'site',
                    'referral',
                    'status',
                    'amount_courses',
                    'amount_teachers',
                    'amount_reviews',
                    'image_logo',
                    'image_site',
                ])
                    ->where('link', $this->link)
                    ->active()
                    ->onlyWithCourses()
                    ->withCount([
                        'reviews' => function ($query) {
                            $query->where('status', ReviewStatus::ACTIVE->value);
                        },
                        'reviews as reviews_1_star_count' => function ($query) {
                            $query
                                ->where('status', Status::ACTIVE->value)
                                ->where('rating', 1);
                        },
                        'reviews as reviews_2_stars_count' => function ($query) {
                            $query
                                ->where('status', Status::ACTIVE->value)
                                ->where('rating', 2);
                        },
                        'reviews as reviews_3_stars_count' => function ($query) {
                            $query
                                ->where('status', Status::ACTIVE->value)
                                ->where('rating', 3);
                        },
                        'reviews as reviews_4_stars_count' => function ($query) {
                            $query
                                ->where('status', Status::ACTIVE->value)
                                ->where('rating', 4);
                        },
                        'reviews as reviews_5_stars_count' => function ($query) {
                            $query
                                ->where('status', Status::ACTIVE->value)
                                ->where('rating', 5);
                        },
                    ])
                    ->with('metatag')
                    ->withCount('reviews')
                    ->first();

                return $school ? SchoolEntity::from($school->toArray()) : null;
            }
        );
    }
}
