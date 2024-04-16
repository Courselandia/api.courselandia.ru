<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Actions\Site;

use App\Modules\Course\Enums\Status;
use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Category\Entities\Category as CategoryEntity;
use App\Modules\Category\Models\Category;

/**
 * Класс действия для получения категории.
 */
class CategoryLinkAction extends Action
{
    /**
     * ID категории.
     *
     * @var string
     */
    private string $link;

    /**
     * @param string $link ID категории.
     */
    public function __construct(string $link)
    {
        $this->link = $link;
    }

    /**
     * Метод запуска логики.
     *
     * @return CategoryEntity|null Вернет результаты исполнения.
     */
    public function run(): ?CategoryEntity
    {
        $cacheKey = Util::getKey('category', 'admin', 'get', $this->link);

        return Cache::tags(['catalog', 'category'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $result = Category::where('link', $this->link)
                    ->whereHas('courses', function ($query) {
                        $query->where('status', Status::ACTIVE->value)
                            ->where('has_active_school', true);
                    })
                    ->active()
                    ->with([
                        'metatag',
                        'directions' => function ($query) {
                            $query->where('status', true)
                                ->whereHas('courses', function ($query) {
                                    $query->where('status', Status::ACTIVE->value)
                                        ->where('has_active_school', true);
                                });
                        },
                        'professions' => function ($query) {
                            $query->where('status', true)
                                ->whereHas('courses', function ($query) {
                                    $query->where('status', Status::ACTIVE->value)
                                        ->where('has_active_school', true);
                                });
                        },
                    ])
                    ->first();

                if ($result) {
                    return CategoryEntity::from($result->toArray());
                }

                return null;
            }
        );
    }
}
