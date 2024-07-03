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
use App\Modules\Teacher\Entities\Teacher as TeacherEntity;
use App\Modules\Teacher\Models\Teacher;

/**
 * Класс действия для получения категории.
 */
class TeacherGetAction extends Action
{
    /**
     * ID учителя.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * @param int|string $id ID учителя.
     */
    public function __construct(int|string $id)
    {
        $this->id = $id;
    }

    /**
     * Метод запуска логики.
     *
     * @return TeacherEntity|null Вернет результаты исполнения.
     */
    public function run(): ?TeacherEntity
    {
        $cacheKey = Util::getKey('teacher', 'admin', 'get', $this->id);

        return Cache::tags(['catalog', 'teacher'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $result = Teacher::select([
                    'id',
                    'metatag_id',
                    'name',
                    'link',
                    'copied',
                    'city',
                    'comment',
                    'additional',
                    'text',
                    'rating',
                    'status',
                    'image_cropped_options',
                    'image_small',
                    'image_middle',
                    'image_big',
                ])
                    ->with([
                    'metatag',
                    'directions',
                    'schools',
                    'experiences',
                    'socialMedias',
                ])->find($this->id);

                return $result ? TeacherEntity::from($result->toArray()) : null;
            }
        );
    }
}
