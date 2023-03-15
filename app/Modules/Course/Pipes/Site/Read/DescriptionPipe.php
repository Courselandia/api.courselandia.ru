<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Pipes\Site\Read;

use Util;
use Cache;
use Closure;
use App\Models\Entity;
use App\Models\Contracts\Pipe;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Category\Actions\Site\CategoryLinkAction;
use App\Modules\Direction\Actions\Site\DirectionLinkAction;
use App\Modules\Profession\Actions\Site\ProfessionLinkAction;
use App\Modules\School\Actions\Site\School\SchoolLinkAction;
use App\Modules\Skill\Actions\Site\SkillLinkAction;
use App\Modules\Teacher\Actions\Site\TeacherLinkAction;
use App\Modules\Tool\Actions\Site\ToolLinkAction;
use App\Modules\Course\Entities\CourseRead;

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
                    $action = app(DirectionLinkAction::class);
                    $action->link = $link;

                    return $action->run();
                }

                if ($section === 'category') {
                    $action = app(CategoryLinkAction::class);
                    $action->link = $link;

                    return $action->run();
                }

                if ($section === 'profession') {
                    $action = app(ProfessionLinkAction::class);
                    $action->link = $link;

                    return $action->run();
                }

                if ($section === 'school') {
                    $action = app(SchoolLinkAction::class);
                    $action->link = $link;

                    return $action->run();
                }

                if ($section === 'teacher') {
                    $action = app(TeacherLinkAction::class);
                    $action->link = $link;

                    return $action->run();
                }

                if ($section === 'tool') {
                    $action = app(ToolLinkAction::class);
                    $action->link = $link;

                    return $action->run();
                }

                if ($section === 'skill') {
                    $action = app(SkillLinkAction::class);
                    $action->link = $link;

                    return $action->run();
                }

                return null;
            }
        );

        return $next($entity);
    }
}
