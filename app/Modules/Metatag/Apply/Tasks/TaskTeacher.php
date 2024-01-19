<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Apply\Tasks;

use Throwable;
use App\Modules\Teacher\Models\Teacher;
use App\Modules\Metatag\Apply\Apply;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Apply\Task;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;

/**
 * Класс задание для назначения метатэгов для учителей.
 */
class TaskTeacher extends Task
{
    /**
     * Шаблон title мэтатега.
     *
     * @var string
     */
    private string $title_template = 'Преподаватель {teacher} — отзывы, рейтинг[countTeacherCourses:, список из {countTeacherCourses:курс|genitive}] — Courselandia';

    /**
     * Шаблон описания мэтатега.
     *
     * @var string
     */
    private string $description_template = 'Все курсы преподавателя {teacher} — полный список обучающих онлайн-курсов в каталоге Courselandia.';

    /**
     * Возвращает количество обрабатываемых записей.
     *
     * @return int Количество.
     */
    public function count(): int
    {
        return Teacher::whereHas('courses', function ($query) {
            $query->where('status', Status::ACTIVE->value)
                ->whereHas('school', function ($query) {
                    $query->where('status', true);
                });
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

        $query = Teacher::with([
            'metatag',
        ])
            ->whereHas('courses', function ($query) {
                $query->where('status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('status', true);
                    });
            })
            ->where('status', true);

        for ($i = 0; $i < $count; $i++) {
            $teacher = $query->clone()
                ->offset($i)
                ->limit(1)
                ->first();

            if ($teacher) {
                sleep(Apply::SLEEP);
                /**
                 * @var Teacher $teacher
                 */
                $countTeacherCourses = Course::where('courses.status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('schools.status', true);
                    })
                    ->whereHas('teachers', function ($query) use ($teacher) {
                        $query->where('teachers.id', $teacher->id);
                    })
                    ->count();

                $template = new Template();
                $templateValues = [
                    'teacher' => $teacher->name,
                    'countTeacherCourses' => $countTeacherCourses,
                ];

                $action = app(MetatagSetAction::class);

                if ($this->onlyUpdate()) {
                    $action->description = $teacher->metatag?->description_template
                        ? $template->convert($teacher->metatag?->description_template, $templateValues)
                        : null;

                    $action->title = $teacher->metatag?->title_template
                        ? $template->convert($teacher->metatag?->title_template, $templateValues)
                        : null;

                    $action->description_template = $teacher->metatag?->description_template;
                    $action->title_template = $teacher->metatag?->title_template;
                } else {
                    $action->description = $template->convert($this->description_template, $templateValues);
                    $action->title = $template->convert($this->title_template, $templateValues);
                    $action->description_template = $this->description_template;
                    $action->title_template = $this->title_template;
                }

                $action->keywords = $teacher->metatag?->keywords;
                $action->id = $teacher->metatag_id ?: null;
                $metatagId = $action->run()->id;
                $teacher->metatag_id = $metatagId;

                try {
                    $teacher->save();

                    if ($read) {
                        $read();
                    }
                } catch (Throwable $error) {
                    $this->addError('Ошибка генерации метатэгов для учителя ' . $teacher->name . ': ' . $error->getMessage());
                }
            }
        }
    }
}
