<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Apply\Tasks;

use Throwable;
use App\Modules\Profession\Models\Profession;
use App\Modules\Metatag\Apply\Apply;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Apply\Task;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;

/**
 * Класс задание для назначения метатэгов для профессий.
 */
class TaskProfession extends Task
{
    /**
     * Шаблон title мэтатега.
     *
     * @var string
     */
    private string $title_template = 'Каталог онлайн-курсов по профессии {profession:nominative} [countProfessionCourses:— {countProfessionCourses:курс|nominative} для обучения] — Courselandia';

    /**
     * Шаблон описания мэтатега.
     *
     * @var string
     */
    private string $description_template = 'Найдите для себя онлайн-курс по профессии {profession:nominative} из каталога Courselandia [countProfessionCourses:— {countProfessionCourses:курс|nominative} на выбор]. Рейтинг онлайн-школ и курсов, легкий поиск, сравнение цен.';

    /**
     * Шаблон заголовка.
     *
     * @var string
     */
    private string $header_template = 'Онлайн-курсы по профессии {profession:nominative}';

    /**
     * Возвращает количество обрабатываемых записей.
     *
     * @return int Количество.
     */
    public function count(): int
    {
        return Profession::whereHas('courses', function ($query) {
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

        $query = Profession::with([
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
            $profession = $query->clone()
                ->offset($i)
                ->limit(1)
                ->first();

            if ($profession) {
                sleep(Apply::SLEEP);
                /**
                 * @var Profession $profession
                 */
                $countProfessionCourses = Course::where('courses.status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('schools.status', true);
                    })
                    ->whereHas('professions', function ($query) use ($profession) {
                        $query->where('professions.id', $profession->id);
                    })
                    ->count();

                $template = new Template();
                $templateValues = [
                    'profession' => $profession->name,
                    'countProfessionCourses' => $countProfessionCourses,
                ];

                $action = app(MetatagSetAction::class);

                if ($this->onlyUpdate()) {
                    $action->description = $profession->metatag?->description_template
                        ? $template->convert($profession->metatag?->description_template, $templateValues)
                        : null;

                    $action->title = $profession->metatag?->title_template
                        ? $template->convert($profession->metatag?->title_template, $templateValues)
                        : null;

                    $profession->header = $profession->header_template
                        ? $template->convert($profession->header_template, $templateValues)
                        : null;

                    $action->description_template = $profession->metatag?->description_template;
                    $action->title_template = $profession->metatag?->title_template;
                } else {
                    $action->description = $template->convert($this->description_template, $templateValues);
                    $action->title = $template->convert($this->title_template, $templateValues);
                    $profession->header = $template->convert($this->header_template, $templateValues);
                    $action->description_template = $this->description_template;
                    $action->title_template = $this->title_template;
                    $profession->header_template = $this->header_template;
                }

                $action->keywords = $profession->metatag?->keywords;
                $action->id = $profession->metatag_id ?: null;
                $metatagId = $action->run()->id;
                $profession->metatag_id = $metatagId;

                try {
                    $profession->save();

                    if ($read) {
                        $read();
                    }
                } catch (Throwable $error) {
                    $this->addError('Ошибка генерации метатэгов для профессии ' . $profession->name . ': ' . $error->getMessage());
                }
            }
        }
    }
}
