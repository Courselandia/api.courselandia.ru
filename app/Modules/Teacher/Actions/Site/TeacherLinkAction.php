<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Actions\Site;

use App\Modules\Course\Enums\Status;
use Cache;
use Util;
use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Teacher\Entities\Teacher as TeacherEntity;
use App\Modules\Teacher\Models\Teacher;

/**
 * Класс действия для получения категории.
 */
class TeacherLinkAction extends Action
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
     * @return TeacherEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?TeacherEntity
    {
        $cacheKey = Util::getKey('teacher', 'admin', 'get', $this->link);

        return Cache::tags(['catalog', 'teacher', 'directions', 'schools', 'category'])->remember(
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
                    $item = $result->toArray();
                    $entity = new TeacherEntity();
                    $entity->set($item);

                    return $entity;
                }

                return null;
            }
        );
    }
}
