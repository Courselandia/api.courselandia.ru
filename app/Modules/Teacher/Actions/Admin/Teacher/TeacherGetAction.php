<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Actions\Admin\Teacher;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Modules\Teacher\Entities\Teacher as TeacherEntity;
use App\Modules\Teacher\Models\Teacher;
use Cache;
use Util;

/**
 * Класс действия для получения учителя.
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
        $cacheKey = Util::getKey('teacher', $this->id, 'metatag', 'directions', 'schools');

        return Cache::tags(['catalog', 'teacher'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $teacher = Teacher::where('id', $this->id)
                    ->with([
                        'metatag',
                        'directions',
                        'schools',
                        'experiences',
                        'socialMedias',
                        'analyzers',
                    ])
                    ->first();

                return $teacher ? TeacherEntity::from($teacher->toArray()) : null;
            }
        );
    }
}
