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
use App\Modules\Direction\Models\Direction;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Apply\Task;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;

/**
 * Класс задание для назначения метатэгов для направлений.
 */
class TaskDirection extends Task
{
    /**
     * Шаблон title мэтатега.
     *
     * @var string
     */
    private string $title_template = 'Каталог онлайн-курсов по {direction:dative}[countDirectionCourses:: {countDirectionCourses:курс|nominative} для обучения] — Courselandia';

    /**
     * Шаблон описания мэтатега.
     *
     * @var string
     */
    private string $description_template = 'В каталоге Courselandia вы можете найти интересные курсы по направлению {direction:nominative} [countDirectionCourses:из {countDirectionCourses:вариант|genitive}]. Здесь полное описание курсов, удобный поиск, рейтинги, обучающие программы.';

    /**
     * Шаблон заголовка.
     *
     * @var string
     */
    private string $header_template = 'Онлайн курсы по {direction:dative}';

    /**
     * Возвращает количество обрабатываемых записей.
     *
     * @return int Количество.
     */
    public function count(): int
    {
        return Direction::whereHas('courses', function ($query) {
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

        $query = Direction::with([
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
            $direction = $query->clone()
                ->offset($i)
                ->limit(1)
                ->first();

            if ($direction) {
                sleep(Apply::SLEEP);
                /**
                 * @var Direction $direction
                 */
                $countDirectionCourses = Course::where('courses.status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('schools.status', true);
                    })
                    ->whereHas('directions', function ($query) use ($direction) {
                        $query->where('directions.id', $direction->id);
                    })
                    ->count();

                $template = new Template();
                $templateValues = [
                    'direction' => $direction->name,
                    'countDirectionCourses' => $countDirectionCourses,
                ];

                $action = app(MetatagSetAction::class);

                if ($this->onlyUpdate()) {
                    $action->description = $direction->metatag?->description_template
                        ? $template->convert($direction->metatag?->description_template, $templateValues)
                        : null;

                    $action->title = $direction->metatag?->title_template
                        ? $template->convert($direction->metatag?->title_template, $templateValues)
                        : null;

                    $direction->header = $direction->header_template
                        ? $template->convert($direction->header_template, $templateValues)
                        : null;

                    $action->description_template = $direction->metatag?->description_template;
                    $action->title_template = $direction->metatag?->title_template;
                } else {
                    $action->description = $template->convert($this->description_template, $templateValues);
                    $action->title = $template->convert($this->title_template, $templateValues);
                    $direction->header = $template->convert($this->header_template, $templateValues);
                    $action->description_template = $this->description_template;
                    $action->title_template = $this->title_template;
                    $direction->header_template = $this->header_template;
                }

                $action->keywords = $direction->metatag?->keywords;
                $action->id = $direction->metatag_id ?: null;
                $metatagId = $action->run()->id;
                $direction->metatag_id = $metatagId;

                try {
                    $direction->save();

                    if ($read) {
                        $read();
                    }
                } catch (Throwable $error) {
                    $this->addError('Ошибка генерации метатэгов для направления ' . $direction->name . ': ' . $error->getMessage());
                }
            }
        }
    }
}
