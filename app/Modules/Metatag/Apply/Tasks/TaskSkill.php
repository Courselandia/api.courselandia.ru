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
use App\Modules\Skill\Models\Skill;
use App\Modules\Metatag\Apply\Apply;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Apply\Task;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;

/**
 * Класс задание для назначения метатэгов для навыков.
 */
class TaskSkill extends Task
{
    /**
     * Шаблон title мэтатега.
     *
     * @var string
     */
    private string $title_template = 'Каталог онлайн-курсов по {skill:dative}[countSkillCourses:: {countSkillCourses:курс|nominative} для обучения] — Courselandia';

    /**
     * Шаблон описания мэтатега.
     *
     * @var string
     */
    private string $description_template = 'Подберите обучающий онлайн-курс для получения навыка {skill:nominative} из каталога Courselandia [countSkillCourses:— {countSkillCourses:курс|nominative} для вас]. Сравнение цен, рейтинг онлайн-школ, сравнение курсов.';

    /**
     * Шаблон заголовка.
     *
     * @var string
     */
    private string $header_template = 'Онлайн курсы по {skill:dative}';

    /**
     * Возвращает количество обрабатываемых записей.
     *
     * @return int Количество.
     */
    public function count(): int
    {
        return Skill::whereHas('courses', function ($query) {
            $query->where('status', Status::ACTIVE->value)
                ->whereHas('school', function ($query) {
                    $query->active()
                        ->withCourses();
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

        $query = Skill::with([
            'metatag',
        ])
            ->whereHas('courses', function ($query) {
                $query->where('status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->active()
                            ->withCourses();
                    });
            })
            ->where('status', true);

        for ($i = 0; $i < $count; $i++) {
            $skill = $query->clone()
                ->offset($i)
                ->limit(1)
                ->first();

            if ($skill) {
                sleep(Apply::SLEEP);
                /**
                 * @var Skill $skill
                 */
                $countSkillCourses = Course::where('courses.status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->active()
                            ->withCourses();
                    })
                    ->whereHas('skills', function ($query) use ($skill) {
                        $query->where('skills.id', $skill->id);
                    })
                    ->count();

                $template = new Template();
                $templateValues = [
                    'skill' => $skill->name,
                    'countSkillCourses' => $countSkillCourses,
                ];

                $dataMetatagSet = new MetatagSet();

                if ($this->onlyUpdate()) {
                    $dataMetatagSet->description = $skill->metatag?->description_template
                        ? $template->convert($skill->metatag?->description_template, $templateValues)
                        : null;

                    $dataMetatagSet->title = $skill->metatag?->title_template
                        ? $template->convert($skill->metatag?->title_template, $templateValues)
                        : null;

                    $skill->header = $skill->header_template
                        ? $template->convert($skill->header_template, $templateValues)
                        : null;

                    $dataMetatagSet->description_template = $skill->metatag?->description_template;
                    $dataMetatagSet->title_template = $skill->metatag?->title_template;
                } else {
                    $dataMetatagSet->description = $template->convert($this->description_template, $templateValues);
                    $dataMetatagSet->title = $template->convert($this->title_template, $templateValues);
                    $skill->header = $template->convert($this->header_template, $templateValues);
                    $dataMetatagSet->description_template = $this->description_template;
                    $dataMetatagSet->title_template = $this->title_template;
                    $skill->header_template = $this->header_template;
                }

                $dataMetatagSet->keywords = $skill->metatag?->keywords;
                $dataMetatagSet->id = $skill->metatag_id ?: null;

                $action = new MetatagSetAction($dataMetatagSet);

                $metatagId = $action->run()->id;
                $skill->metatag_id = $metatagId;

                try {
                    $skill->save();

                    if ($read) {
                        $read();
                    }
                } catch (Throwable $error) {
                    $this->addError('Ошибка генерации метатэгов для навыка ' . $skill->name . ': ' . $error->getMessage());
                }
            }
        }
    }
}
