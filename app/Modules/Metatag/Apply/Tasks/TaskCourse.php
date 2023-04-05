<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Apply\Tasks;

use Throwable;
use App\Modules\Metatag\Apply\Apply;
use App\Modules\Course\Enums\Currency;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Apply\Task;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;

/**
 * Класс задание для назначения метатэгов для курсов.
 */
class TaskCourse extends Task
{
    /**
     * Шаблон title мэтатега.
     *
     * @var string
     */
    private string $title_template = 'Курс {course} от {school:genitive} [price:по цене {price}/бесплатно] — Courselandia';

    /**
     * Шаблон описания мэтатега.
     *
     * @var string
     */
    private string $description_template = 'Начните обучение онлайн-курса {course} от {school:genitive} прям сейчас выбрав его в каталоге Courselandia, легкий поиск, возможность сравнивать курсы по разным параметрам.';

    /**
     * Шаблон заголовка.
     *
     * @var string
     */
    private string $header_template = '{course} от {school:genitive}';

    /**
     * Возвращает количество обрабатываемых записей.
     *
     * @return int Количество.
     */
    public function count(): int
    {
        return Course::where('status', Status::ACTIVE->value)
            ->whereHas('school', function ($query) {
                $query->where('status', true);
            })
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

        $query = Course::with([
            'metatag',
            'school',
        ])
        ->where('status', Status::ACTIVE->value)
        ->whereHas('school', function ($query) {
            $query->where('status', true);
        });

        for ($i = 0; $i < $count; $i++) {
            $course = $query->clone()
                ->offset($i)
                ->limit(1)
                ->first();

            if ($course) {
                sleep(Apply::SLEEP);
                /**
                 * @var Course $course
                 */
                $template = new Template();
                $templateValues = [
                    'course' => $course->name,
                    'school' => $course->school->name,
                    'price' => $course->price,
                    'currency' => Currency::from($course->currency),
                ];

                $action = app(MetatagSetAction::class);

                if ($this->onlyUpdate()) {
                    $action->description = $course->metatag?->description_template
                        ? $template->convert($course->metatag?->description_template, $templateValues)
                        : null;

                    $action->title = $course->metatag?->title_template
                        ? $template->convert($course->metatag?->title_template, $templateValues)
                        : null;

                    $course->header = $course->header_template
                        ? $template->convert($course->header_template, $templateValues)
                        : null;

                    $action->description_template = $course->metatag?->description_template;
                    $action->title_template = $course->metatag?->title_template;
                } else {
                    $action->description = $template->convert($this->description_template, $templateValues);
                    $action->title = $template->convert($this->title_template, $templateValues);
                    $course->header = $template->convert($this->header_template, $templateValues);
                    $action->description_template = $this->description_template;
                    $action->title_template = $this->title_template;
                    $course->header_template = $this->header_template;
                }

                $action->keywords = $course->metatag?->keywords;
                $action->id = $course->metatag_id ?: null;
                $metatagId = $action->run()->id;
                $course->metatag_id = $metatagId;

                try {
                    $course->save();

                    if ($read) {
                        $read();
                    }
                } catch (Throwable $error) {
                    $this->addError('Ошибка генерации метатэгов для курса ' . $course->name . ': ' . $error->getMessage());
                }
            }
        }
    }
}
