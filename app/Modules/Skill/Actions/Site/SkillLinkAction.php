<?php
/**
 * Модуль Навыков.
 * Этот модуль содержит все классы для работы с навыками.
 *
 * @package App\Modules\Skill
 */

namespace App\Modules\Skill\Actions\Site;

use App\Modules\Course\Enums\Status;
use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Skill\Entities\Skill as SkillEntity;
use App\Modules\Skill\Models\Skill;

/**
 * Класс действия для получения категории.
 */
class SkillLinkAction extends Action
{
    /**
     * Ссылка навыка.
     *
     * @var string
     */
    private string $link;

    /**
     * @param string $link Ссылка навыка.
     */
    public function __construct(string $link)
    {
        $this->link = $link;
    }

    /**
     * Метод запуска логики.
     *
     * @return SkillEntity|null Вернет результаты исполнения.
     */
    public function run(): ?SkillEntity
    {
        $cacheKey = Util::getKey('skill', 'site', 'get', $this->link);

        return Cache::tags(['catalog', 'skill'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $result = Skill::where('link', $this->link)
                    ->active()
                    ->whereHas('courses', function ($query) {
                        $query->where('status', Status::ACTIVE->value)
                            ->where('has_active_school', true);
                    })
                    ->with([
                        'metatag',
                    ])
                    ->first();

                return $result ? SkillEntity::from($result->toArray()) : null;
            }
        );
    }
}
