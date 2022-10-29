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
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Teacher\Entities\Teacher as TeacherEntity;
use App\Modules\Teacher\Repositories\Teacher;
use Cache;
use ReflectionException;
use Util;

/**
 * Класс действия для получения учителя.
 */
class TeacherGetAction extends Action
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
     * @return TeacherEntity|null Вернет результаты исполнения.
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): ?TeacherEntity
    {
        $query = new RepositoryQueryBuilder();
        $query->setId($this->id)
            ->setRelations([
                'metatag',
                'directions',
                'schools'
            ]);

        $cacheKey = Util::getKey('teacher', $query);

        Cache::flush();

        return Cache::tags(['catalog', 'teacher', 'direction', 'school'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->teacher->get($query);
            }
        );
    }
}
