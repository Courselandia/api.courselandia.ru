<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Actions\Site;

use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Teacher\Entities\Teacher as TeacherEntity;
use App\Modules\Teacher\Models\Teacher;
use App\Modules\Course\Enums\Status;

/**
 * Класс действия для получения категории.
 */
class TeacherLinkAction extends Action
{
    /**
     * Ссылка учителя.
     *
     * @var string
     */
    private string $link;

    /**
     * @param string $link Ссылка учителя.
     */
    public function __construct(string $link)
    {
        $this->link = $link;
    }

    /**
     * Метод запуска логики.
     *
     * @return TeacherEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?TeacherEntity
    {
        $cacheKey = Util::getKey('teacher', 'admin', 'get', $this->link);

        return Cache::tags(['catalog', 'teacher'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $result = Teacher::where('link', $this->link)
                    ->active()
                    ->whereHas('courses', function ($query) {
                        $query->where('status', Status::ACTIVE->value)
                            ->where('has_active_school', true);
                    })
                    ->with([
                        'metatag',
                        'schools',
                        'experiences',
                        'socialMedias',
                        'schools' => function ($query) {
                            $query->where('status', true)
                                ->whereHas('courses', function ($query) {
                                    $query->where('status', Status::ACTIVE->value);
                                });
                        },
                        'directions' => function ($query) {
                            $query->where('status', true)
                                ->whereHas('courses', function ($query) {
                                    $query->where('status', Status::ACTIVE->value)
                                        ->where('has_active_school', true);
                                });
                        },
                    ])->first();

                if ($result) {
                    return TeacherEntity::from($result->toArray());
                }

                return null;
            }
        );
    }
}
