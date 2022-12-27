<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

use App\Models\Enums\CacheTime;
use Util;
use App\Modules\Category\Models\Category;
use App\Modules\Category\Entities\Category as CategoryEntity;
use App\Modules\Direction\Models\Direction;
use App\Modules\Direction\Entities\Direction as DirectionEntity;
use App\Modules\Profession\Models\Profession;
use App\Modules\Profession\Entities\Profession as ProfessionEntity;
use App\Modules\School\Models\School;
use App\Modules\School\Entities\School as SchoolEntity;
use App\Modules\Skill\Models\Skill;
use App\Modules\Skill\Entities\Skill as SkillEntity;
use App\Modules\Teacher\Models\Teacher;
use App\Modules\Teacher\Entities\Teacher as TeacherEntity;
use App\Modules\Tool\Models\Tool;
use App\Modules\Tool\Entities\Tool as ToolEntity;
use Closure;
use App\Models\Contracts\Pipe;
use App\Models\Entity;
use App\Modules\Course\Entities\CourseRead;
use Eloquent;
use Cache;

/**
 * Чтение курсов: описание.
 */
class DescriptionPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Entity|CourseRead $entity Сущность.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     */
    public function handle(Entity|CourseRead $entity, Closure $next): mixed
    {
        $descriptionByFilters = [
            'categories-id' => [
                'model' => Category::class,
                'entity' => CategoryEntity::class,
                'name' => 'category',
            ],
            'directions-id' => [
                'model' => Direction::class,
                'entity' => DirectionEntity::class,
                'name' => 'direction',
            ],
            'professions-id' => [
                'model' => Profession::class,
                'entity' => ProfessionEntity::class,
                'name' => 'profession',
            ],
            'school-id' => [
                'model' => School::class,
                'entity' => SchoolEntity::class,
                'name' => 'school',
            ],
            'skills-id' => [
                'model' => Skill::class,
                'entity' => SkillEntity::class,
                'name' => 'skill',
            ],
            'teachers-id' => [
                'model' => Teacher::class,
                'entity' => TeacherEntity::class,
                'name' => 'teacher',
            ],
            'tools-id' => [
                'model' => Tool::class,
                'entity' => ToolEntity::class,
                'name' => 'tool',
            ],
        ];

        $model = null;
        $modelEntity = null;
        $id = null;
        $descriptionName = null;

        foreach ($descriptionByFilters as $filterName => $item) {
            if (
                isset($entity->filters[$filterName])
                && ((
                        is_array($entity->filters[$filterName])
                        && count($entity->filters[$filterName]) === 1
                    ) || (
                        !is_array($entity->filters[$filterName])
                        && $entity->filters[$filterName]
                    ))
            ) {
                $model = $item['model'];
                $modelEntity = $item['entity'];
                $filters = is_array($entity->filters[$filterName]) ? $entity->filters[$filterName] : [$entity->filters[$filterName]];
                $id = $filters[0];
                $descriptionName = $item['name'];

                break;
            }
        }

        if ($model) {
            $cacheKey = Util::getKey(
                'course',
                'site',
                'description',
                $descriptionName,
                $id,
            );

            $record = Cache::tags([
                'course',
                'direction',
                'profession',
                'category',
                'skill',
                'teacher',
                'tool',
                'review',
            ])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($model, $id) {
                    /**
                     * @var $model Eloquent
                     */
                    $item = $model::find($id);

                    return $item ? $item->toArray() : null;
                }
            );

            if ($record) {
                $entity->description = new $modelEntity($record);
                $entity->section = $descriptionName;
            }
        }

        return $next($entity);
    }
}
