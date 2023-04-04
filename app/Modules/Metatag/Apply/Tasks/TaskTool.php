<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Apply\Tasks;

use Throwable;
use App\Modules\Tool\Models\Tool;
use App\Modules\Metatag\Apply\Apply;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Apply\Task;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;

/**
 * Класс задание для назначения метатэгов для инструментов.
 */
class TaskTool extends Task
{
    /**
     * Шаблон title мэтатега.
     *
     * @var string
     */
    private string $title_template = 'Онлайн-курсы по изучению инструмента {tool:nominative}[countToolCourses:: {countToolCourses:курс|nominative} для обучения с нуля] — Courselandia';

    /**
     * Шаблон описания мэтатега.
     *
     * @var string
     */
    private string $description_template = 'Выбирайте обучающий онлайн-курс по изучению инструмента {tool:nominative} из каталога Courselandia [countToolCourses:— {countToolCourses:курс|nominative} на ваш выбор]. Рейтинг онлайн-школ, сравнение цен, легкий поиск онлайн-курсов.';

    /**
     * Шаблон заголовка.
     *
     * @var string
     */
    private string $header_template = 'Онлайн-курсы по изучению инструмента {tool:nominative}';

    /**
     * Возвращает количество обрабатываемых записей.
     *
     * @return int Количество.
     */
    public function count(): int
    {
        return Tool::whereHas('courses', function ($query) {
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

        $query = Tool::with([
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
            $tool = $query->clone()
                ->offset($i)
                ->limit(1)
                ->first();

            if ($tool) {
                sleep(Apply::SLEEP);
                /**
                 * @var Tool $tool
                 */
                $countToolCourses = Course::where('courses.status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('schools.status', true);
                    })
                    ->whereHas('tools', function ($query) use ($tool) {
                        $query->where('tools.id', $tool->id);
                    })
                    ->count();

                $template = new Template();
                $templateValues = [
                    'tool' => $tool->name,
                    'countToolCourses' => $countToolCourses,
                ];

                $action = app(MetatagSetAction::class);

                if ($this->onlyUpdate()) {
                    $action->description = $tool->metatag?->description_template
                        ? $template->convert($tool->metatag?->description_template, $templateValues)
                        : null;

                    $action->title = $tool->metatag?->title_template
                        ? $template->convert($tool->metatag?->title_template, $templateValues)
                        : null;

                    $tool->header = $tool->header_template
                        ? $template->convert($tool->header_template, $templateValues)
                        : null;

                    $action->description_template = $tool->metatag?->description_template;
                    $action->title_template = $tool->metatag?->title_template;
                } else {
                    $action->description = $template->convert($this->description_template, $templateValues);
                    $action->title = $template->convert($this->title_template, $templateValues);
                    $tool->header = $template->convert($this->header_template, $templateValues);
                    $action->description_template = $this->description_template;
                    $action->title_template = $this->title_template;
                    $tool->header_template = $this->header_template;
                }

                $action->keywords = $tool->metatag?->keywords;
                $action->id = $tool->metatag_id ?: null;
                $metatagId = $action->run()->id;
                $tool->metatag_id = $metatagId;

                try {
                    $tool->save();

                    if ($read) {
                        $read();
                    }
                } catch (Throwable $error) {
                    $this->addError('Ошибка генерации метатэгов для инструмента ' . $tool->name . ': ' . $error->getMessage());
                }
            }
        }
    }
}
