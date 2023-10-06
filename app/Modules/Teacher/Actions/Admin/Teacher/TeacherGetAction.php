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
use App\Models\Exceptions\ParameterInvalidException;
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
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Метод запуска логики.
     *
     * @return TeacherEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): ?TeacherEntity
    {
        $cacheKey = Util::getKey('teacher', $this->id, 'metatag', 'directions', 'schools');

        return Cache::tags(['catalog', 'teacher', 'direction', 'school'])->remember(
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
                    ])
                    ->first();

                return $teacher ? new TeacherEntity($teacher->toArray()) : null;
            }
        );
    }
}
