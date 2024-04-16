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
use App\Modules\Tool\Models\Tool;
use App\Modules\Metatag\Apply\Apply;
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
     * @throws TemplateException
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

                $dataMetatagSet = new MetatagSet();

                if ($this->onlyUpdate()) {
                    $dataMetatagSet->description = $tool->metatag?->description_template
                        ? $template->convert($tool->metatag?->description_template, $templateValues)
                        : null;

                    $dataMetatagSet->title = $tool->metatag?->title_template
                        ? $template->convert($tool->metatag?->title_template, $templateValues)
                        : null;

                    $tool->header = $tool->header_template
                        ? $template->convert($tool->header_template, $templateValues)
                        : null;

                    $dataMetatagSet->description_template = $tool->metatag?->description_template;
                    $dataMetatagSet->title_template = $tool->metatag?->title_template;
                } else {
                    $dataMetatagSet->description = $template->convert($this->description_template, $templateValues);
                    $dataMetatagSet->title = $template->convert($this->title_template, $templateValues);
                    $tool->header = $template->convert($this->header_template, $templateValues);
                    $dataMetatagSet->description_template = $this->description_template;
                    $dataMetatagSet->title_template = $this->title_template;
                    $tool->header_template = $this->header_template;
                }

                $dataMetatagSet->keywords = $tool->metatag?->keywords;
                $dataMetatagSet->id = $tool->metatag_id ?: null;

                $action = new MetatagSetAction($dataMetatagSet);

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
