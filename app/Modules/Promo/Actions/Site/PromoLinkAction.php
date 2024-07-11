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
 * Класс действия для получения школы с промоматериалами.
 */
class PromoLinkAction extends Action
{
    /**
     * Ссылка на школу.
     *
     * @var string
     */
    private string $link;

    /**
     * @param string $link Ссылка на школу.
     */
    public function __construct(string $link)
    {
        $this->link = $link;
    }

    /**
     * Метод запуска логики.
     *
     * @return ?SchoolEntity Вернет результат исполнения.
     */
    public function run(): ?SchoolEntity
    {
        $cacheKey = Util::getKey(
            'school',
            'site',
            'link',
            'promo',
            $this->link,
        );

        return Cache::tags(['catalog', 'promocode', 'promotion', 'school'])->remember(
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
                    ->where('link', $this->link)
                    ->first();

                return $school ? SchoolEntity::from($school) : null;
            }
        );
    }
}
