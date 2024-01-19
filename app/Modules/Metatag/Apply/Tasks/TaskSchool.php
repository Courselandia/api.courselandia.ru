<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Apply\Tasks;

use Throwable;
use App\Modules\School\Models\School;
use App\Modules\Metatag\Apply\Apply;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Apply\Task;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;

/**
 * Класс задание для назначения метатэгов для школ.
 */
class TaskSchool extends Task
{
    /**
     * Шаблон title мэтатега.
     *
     * @var string
     */
    private string $title_template = '{school}:[countSchoolCourses: {countSchoolCourses:онлайн-курс|nominative} — ] цены, сравнения, описание программ и курсов — Courselandia';

    /**
     * Шаблон описания мэтатега.
     *
     * @var string
     */
    private string $description_template = 'Начни учиться в онлайн-школе {school} [countSchoolCourses: — {countSchoolCourses:профессиональный онлайн-курс|nominative} от ведущих преподавателей], подробное описание курсов в каталоге Courselandia.';

    /**
     * Шаблон заголовка.
     *
     * @var string
     */
    private string $header_template = 'Онлайн-курсы школы {school}';

    /**
     * Возвращает количество обрабатываемых записей.
     *
     * @return int Количество.
     */
    public function count(): int
    {
        return School::whereHas('courses', function ($query) {
            $query->where('status', Status::ACTIVE->value);
        })
            ->where('status', true)
            ->count();
    }

    /**
     * Применяем метатэги.
     *
     * @param Callable|null $read Метод, который будет вызван каждый раз при генерации метатэга.
     *
     * @return void
     * @throws TemplateException|ParameterInvalidException
     */
    public function apply(?callable $read = null): void
    {
        $count = $this->count();

        $query = School::with([
            'metatag',
        ])
            ->whereHas('courses', function ($query) {
                $query->where('status', Status::ACTIVE->value);
            })
            ->where('status', true);

        for ($i = 0; $i < $count; $i++) {
            $school = $query->clone()
                ->offset($i)
                ->limit(1)
                ->first();

            if ($school) {
                sleep(Apply::SLEEP);
                /**
                 * @var School $school
                 */
                $countSchoolCourses = Course::where('courses.status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('schools.status', true);
                    })
                    ->whereHas('school', function ($query) use ($school) {
                        $query->where('schools.id', $school->id);
                    })
                    ->count();

                $template = new Template();
                $templateValues = [
                    'school' => $school->name,
                    'countSchoolCourses' => $countSchoolCourses,
                ];

                $action = app(MetatagSetAction::class);

                if ($this->onlyUpdate()) {
                    $action->description = $school->metatag?->description_template
                        ? $template->convert($school->metatag?->description_template, $templateValues)
                        : null;

                    $action->title = $school->metatag?->title_template
                        ? $template->convert($school->metatag?->title_template, $templateValues)
                        : null;

                    $school->header = $school->header_template
                        ? $template->convert($school->header_template, $templateValues)
                        : null;

                    $action->description_template = $school->metatag?->description_template;
                    $action->title_template = $school->metatag?->title_template;
                } else {
                    $action->description = $template->convert($this->description_template, $templateValues);
                    $action->title = $template->convert($this->title_template, $templateValues);
                    $school->header = $template->convert($this->header_template, $templateValues);
                    $action->description_template = $this->description_template;
                    $action->title_template = $this->title_template;
                    $school->header_template = $this->header_template;
                }

                $action->keywords = $school->metatag?->keywords;
                $action->id = $school->metatag_id ?: null;
                $metatagId = $action->run()->id;
                $school->metatag_id = $metatagId;

                try {
                    $school->save();

                    if ($read) {
                        $read();
                    }
                } catch (Throwable $error) {
                    $this->addError('Ошибка генерации метатэгов для школы ' . $school->name . ': ' . $error->getMessage());
                }
            }
        }
    }
}
