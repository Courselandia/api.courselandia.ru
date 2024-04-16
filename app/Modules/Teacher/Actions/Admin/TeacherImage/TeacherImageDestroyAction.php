<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Actions\Admin\TeacherImage;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Teacher\Models\Teacher;
use Cache;
use ImageStore;
use ReflectionException;
use Util;

/**
 * Класс действия для удаления изображения учителя.
 */
class TeacherImageDestroyAction extends Action
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
     * @return bool Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ReflectionException
     */
    public function run(): bool
    {
        $cacheKey = Util::getKey('teacher', 'model', $this->id);

        $teacher = Cache::tags(['catalog', 'teacher'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                return Teacher::find($this->id);
            }
        );

        if ($teacher) {
            if ($teacher->image_small_id) {
                ImageStore::destroy($teacher->image_small_id->id);
            }

            if ($teacher->image_middle_id) {
                ImageStore::destroy($teacher->image_middle_id->id);
            }

            if ($teacher->image_big_id) {
                ImageStore::destroy($teacher->image_big_id->id);
            }

            $teacher->image_small_id = null;
            $teacher->image_middle_id = null;
            $teacher->image_big_id = null;

            $teacher->save();
            Cache::tags(['catalog', 'teacher'])->flush();

            return true;
        }

        throw new RecordNotExistException(
            trans('teacher::actions.admin.teacherImageDestroyAction.notExistTeacher')
        );
    }
}
