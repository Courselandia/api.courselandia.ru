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
use App\Models\Data;
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
use App\Modules\Course\Data\Decorators\CourseRead;

/**
 * Чтение курсов: описание.
 */
class DescriptionPipe implements Pipe
{
    /**
     * Метод, который будет вызван у pipeline.
     *
     * @param Data|CourseRead $data Данные для декоратора для чтения курсов.
     * @param Closure $next Ссылка на следующий pipe.
     *
     * @return mixed Вернет значение полученное после выполнения следующего pipe.
     * @throws ParameterInvalidException
     */
    public function handle(Data|CourseRead $data, Closure $next): mixed
    {
        $cacheKey = Util::getKey(
            'course',
            'site',
            'description',
            $data->section,
            $data->sectionLink,
        );

        $section = $data->section;
        $link = $data->sectionLink;

        $data->description = Cache::tags([
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
                    $action = new DirectionLinkAction($link);

                    return $action->run();
                }

                if ($section === 'category') {
                    $action = new CategoryLinkAction($link);

                    return $action->run();
                }

                if ($section === 'profession') {
                    $action = new ProfessionLinkAction($link);

                    return $action->run();
                }

                if ($section === 'school') {
                    $action = new SchoolLinkAction($link);

                    return $action->run();
                }

                if ($section === 'teacher') {
                    $action = new TeacherLinkAction($link);

                    return $action->run();
                }

                if ($section === 'tool') {
                    $action = new ToolLinkAction($link);

                    return $action->run();
                }

                if ($section === 'skill') {
                    $action = new SkillLinkAction($link);

                    return $action->run();
                }

                return null;
            }
        );

        return $next($data);
    }
}
