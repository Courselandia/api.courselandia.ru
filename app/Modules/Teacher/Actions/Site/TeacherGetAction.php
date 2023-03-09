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

/**
 * Класс действия для получения категории.
 */
class TeacherGetAction extends Action
{
    /**
     * ID категории.
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
        $cacheKey = Util::getKey('teacher', 'admin', 'get', $this->id);

        return Cache::tags(['catalog', 'teacher', 'directions', 'schools', 'category'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                $result = Teacher::with([
                    'metatag',
                    'directions',
                    'schools',
                ])->find($this->id);

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
