<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Apply\Tasks;

use App\Modules\Metatag\Data\MetatagSet;
use Throwable;
use App\Modules\Metatag\Apply\Apply;
use App\Modules\Course\Enums\Currency;
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
                $query->active()
                    ->hasCourses();
            })
            ->count();
    }

    /**
     * Применяем метатэги.
     *
     * @param Callable|null $read Метод, который будет вызван каждый раз при генерации метатэга.
     *
     * @return void
     * @throws TemplateException
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
                $query->active()
                    ->hasCourses();
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

                $dataMetatagSet = new MetatagSet();

                if ($this->onlyUpdate()) {
                    $dataMetatagSet->description = $course->metatag?->description_template
                        ? $template->convert($course->metatag?->description_template, $templateValues)
                        : null;

                    $dataMetatagSet->title = $course->metatag?->title_template
                        ? $template->convert($course->metatag?->title_template, $templateValues)
                        : null;

                    $course->header = $course->header_template
                        ? $template->convert($course->header_template, $templateValues)
                        : null;

                    $dataMetatagSet->description_template = $course->metatag?->description_template;
                    $dataMetatagSet->title_template = $course->metatag?->title_template;
                } else {
                    $dataMetatagSet->description = $template->convert($this->description_template, $templateValues);
                    $dataMetatagSet->title = $template->convert($this->title_template, $templateValues);
                    $course->header = $template->convert($this->header_template, $templateValues);
                    $dataMetatagSet->description_template = $this->description_template;
                    $dataMetatagSet->title_template = $this->title_template;
                    $course->header_template = $this->header_template;
                }

                $dataMetatagSet->keywords = $course->metatag?->keywords;
                $dataMetatagSet->id = $course->metatag_id ?: null;

                $action = new MetatagSetAction($dataMetatagSet);

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
