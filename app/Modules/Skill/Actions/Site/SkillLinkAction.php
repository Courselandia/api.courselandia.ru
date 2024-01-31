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
use App\Models\Exceptions\ParameterInvalidException;
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
     * @throws ParameterInvalidException
     */
    public function run(): ?SkillEntity
    {
        $cacheKey = Util::getKey('skill', 'admin', 'get', $this->link);

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

                if ($result) {
                    return SkillEntity::from($result->toArray());
                }

                return null;
            }
        );
    }
}
