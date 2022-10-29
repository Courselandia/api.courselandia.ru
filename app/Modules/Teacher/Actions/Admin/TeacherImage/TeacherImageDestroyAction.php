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
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Teacher\Repositories\Teacher;
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
     * Репозиторий учителя.
     *
     * @var Teacher
     */
    private Teacher $teacher;

    /**
     * ID учителя.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * Конструктор.
     *
     * @param  Teacher  $teacher  Репозиторий учителя.
     */
    public function __construct(Teacher $teacher)
    {
        $this->teacher = $teacher;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): bool
    {
        $query = new RepositoryQueryBuilder($this->id);
        $cacheKey = Util::getKey('teacher', $query);

        $teacher = Cache::tags(['catalog', 'teacher', 'direction', 'school'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->teacher->get($query);
            }
        );

        if ($teacher) {
            if ($teacher->image_small_id) {
                ImageStore::destroy($teacher->image_small_id->id);
            }

            if ($teacher->image_middle_id) {
                ImageStore::destroy($teacher->image_middle_id->id);
            }

            $teacher->image_small_id = null;
            $teacher->image_middle_id = null;

            $this->teacher->update($this->id, $teacher);
            Cache::tags(['catalog', 'teacher', 'direction', 'school'])->flush();

            return true;
        }

        throw new RecordNotExistException(
            trans('teacher::actions.admin.teacherImageDestroyAction.notExistTeacher')
        );
    }
}
