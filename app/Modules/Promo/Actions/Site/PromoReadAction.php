<?php
/**
 * Модуль промоматериалов.
 * Этот модуль содержит все классы для работы с промоматериалами: промокоды и промоакции.
 *
 * @package App\Modules\Promo
 */

namespace App\Modules\Promo\Actions\Site;

use Util;
use Cache;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\School\Models\School;
use App\Modules\School\Entities\School as SchoolEntity;

/**
 * Класс действия для получения школ с промоматериалами.
 */
class PromoReadAction extends Action
{
    /**
     * Метод запуска логики.
     *
     * @return array Вернет результат исполнения.
     */
    public function run(): array
    {
        $cacheKey = Util::getKey(
            'school',
            'site',
            'read',
            'promo',
            'count',
        );

        return Cache::tags(['catalog', 'promocode', 'promotion', 'school'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $query = School::select([
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
                    ->active()
                    ->hasCourses()
                    ->with([
                        'promocodes' => function ($query) {
                            $query->applicable();
                        },
                        'promotions' => function ($query) {
                            $query->applicable();
                        },
                    ])
                    ->where(function ($query) {
                        $query->whereHas('promocodes', function ($query) {
                            $query->applicable();
                        })
                        ->orWhereHas('promotions', function ($query) {
                            $query->applicable();
                        });
                    })
                    ->active();

                $queryCount = $query->clone();

                $query->orderBy('name', 'ASC');

                $items = $query->get()->toArray();

                return [
                    'data' => SchoolEntity::collect($items),
                    'total' => $queryCount->count(),
                ];
            }
        );
    }
}
