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
use App\Modules\Profession\Models\Profession;
use App\Modules\Metatag\Apply\Apply;
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
                    $query->active()
                        ->hasCourses();
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

        $query = Profession::with([
            'metatag',
        ])
            ->whereHas('courses', function ($query) {
                $query->where('status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->active()
                            ->hasCourses();
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
                        $query->active()
                            ->hasCourses();
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

                $dataMetatagSet = new MetatagSet();

                if ($this->onlyUpdate()) {
                    $dataMetatagSet->description = $profession->metatag?->description_template
                        ? $template->convert($profession->metatag?->description_template, $templateValues)
                        : null;

                    $dataMetatagSet->title = $profession->metatag?->title_template
                        ? $template->convert($profession->metatag?->title_template, $templateValues)
                        : null;

                    $profession->header = $profession->header_template
                        ? $template->convert($profession->header_template, $templateValues)
                        : null;

                    $dataMetatagSet->description_template = $profession->metatag?->description_template;
                    $dataMetatagSet->title_template = $profession->metatag?->title_template;
                } else {
                    $dataMetatagSet->description = $template->convert($this->description_template, $templateValues);
                    $dataMetatagSet->title = $template->convert($this->title_template, $templateValues);
                    $profession->header = $template->convert($this->header_template, $templateValues);
                    $dataMetatagSet->description_template = $this->description_template;
                    $dataMetatagSet->title_template = $this->title_template;
                    $profession->header_template = $this->header_template;
                }

                $dataMetatagSet->keywords = $profession->metatag?->keywords;
                $dataMetatagSet->id = $profession->metatag_id ?: null;

                $action = new MetatagSetAction($dataMetatagSet);

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
