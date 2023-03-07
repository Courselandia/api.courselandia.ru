<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
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
     * @throws ParameterInvalidException
     */
    public function handle(Entity|CourseRead $entity, Closure $next): mixed
    {
        $cacheKey = Util::getKey(
            'course',
            'site',
            'description',
            $entity->section,
            $entity->sectionLink,
        );

        $section = $entity->section;
        $link = $entity->sectionLink;

        $entity->description = Cache::tags([
            'course',
            'direction',
            'profession',
            'category',
            'skill',
            'teacher',
            'tool',
            'process',
            'employment',
            'review',
        ])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($section, $link) {
                if ($section === 'direction') {
                    $data = Direction::where('link', $link)->first();

                    return $data ? new DirectionEntity($data->toArray()) : null;
                }

                if ($section === 'category') {
                    $data = Category::where('link', $link)->first();

                    return $data ? new CategoryEntity($data->toArray()) : null;
                }

                if ($section === 'profession') {
                    $data = Profession::where('link', $link)->first();

                    return $data ? new ProfessionEntity($data->toArray()) : null;
                }

                if ($section === 'school') {
                    $data = School::where('link', $link)->first();

                    return $data ? new SchoolEntity($data->toArray()) : null;
                }

                if ($section === 'teacher') {
                    $data = Teacher::where('link', $link)->first();

                    return $data ? new TeacherEntity($data->toArray()) : null;
                }

                if ($section === 'tool') {
                    $data = Tool::where('link', $link)->first();

                    return $data ? new ToolEntity($data->toArray()) : null;
                }

                if ($section === 'skill') {
                    $data = Skill::where('link', $link)->first();

                    return $data ? new SkillEntity($data->toArray()) : null;
                }

                return null;
            }
        );

        return $next($entity);
    }
}
