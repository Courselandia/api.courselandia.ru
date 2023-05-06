<?php
/**
 * Модуль Профессии.
 * Этот модуль содержит все классы для работы с профессиями.
 *
 * @package App\Modules\Profession
 */

namespace App\Modules\Profession\Actions\Site;

use App\Modules\Course\Enums\Status;
use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Profession\Entities\Profession as ProfessionEntity;
use App\Modules\Profession\Models\Profession;

/**
 * Класс действия для получения категории.
 */
class ProfessionLinkAction extends Action
{
    /**
     * Ссылка категории.
     *
     * @var string|null
     */
    public string|null $link = null;

    /**
     * Метод запуска логики.
     *
     * @return ProfessionEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?ProfessionEntity
    {
        $cacheKey = Util::getKey('profession', 'admin', 'get', $this->link);

        return Cache::tags(['catalog', 'profession'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $result = Profession::where('link', $this->link)
                    ->whereHas('courses', function ($query) {
                        $query->where('status', Status::ACTIVE->value)
                            ->where('has_active_school', true);
                    })
                    ->active()
                    ->with([
                        'metatag',
                    ])->first();

                if ($result) {
                    $item = $result->toArray();
                    $entity = new ProfessionEntity();
                    $entity->set($item);

                    return $entity;
                }

                return null;
            }
        );
    }
}
