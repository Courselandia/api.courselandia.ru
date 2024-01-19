<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Apply\Tasks;

use Throwable;
use App\Modules\Category\Models\Category;
use App\Modules\Metatag\Apply\Apply;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Apply\Task;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;

/**
 * Класс задание для назначения метатэгов для категорий.
 */
class TaskCategory extends Task
{
    /**
     * Шаблон title мэтатега.
     *
     * @var string
     */
    private string $title_template = 'Каталог онлайн-курсов по {category:dative}[countCategoryCourses:: {countCategoryCourses:курс|nominative} для обучения с нуля] — Courselandia';

    /**
     * Шаблон описания мэтатега.
     *
     * @var string
     */
    private string $description_template = 'Выберите обучающий онлайн-курс в категории {category:nominative} в каталоге Courselandia [countCategoryCourses:— {countCategoryCourses:курс|nominative} для вас]. Рейтинги онлайн-школ, сравнение цен, быстрый поиск, сравнение курсов, обучающие программы.';

    /**
     * Шаблон заголовка.
     *
     * @var string
     */
    private string $header_template = 'Онлайн курсы по {category:dative}';

    /**
     * Возвращает количество обрабатываемых записей.
     *
     * @return int Количество.
     */
    public function count(): int
    {
        return Category::whereHas('courses', function ($query) {
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

        $query = Category::with([
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
            $category = $query->clone()
                ->offset($i)
                ->limit(1)
                ->first();

            if ($category) {
                sleep(Apply::SLEEP);
                /**
                 * @var Category $category
                 */
                $countCategoryCourses = Course::where('courses.status', Status::ACTIVE->value)
                    ->whereHas('school', function ($query) {
                        $query->where('schools.status', true);
                    })
                    ->whereHas('categories', function ($query) use ($category) {
                        $query->where('categories.id', $category->id);
                    })
                    ->count();

                $template = new Template();
                $templateValues = [
                    'category' => $category->name,
                    'countCategoryCourses' => $countCategoryCourses,
                ];

                $action = app(MetatagSetAction::class);

                if ($this->onlyUpdate()) {
                    $action->description = $category->metatag?->description_template
                        ? $template->convert($category->metatag?->description_template, $templateValues)
                        : null;

                    $action->title = $category->metatag?->title_template
                        ? $template->convert($category->metatag?->title_template, $templateValues)
                        : null;

                    $category->header = $category->header_template
                        ? $template->convert($category->header_template, $templateValues)
                        : null;

                    $action->description_template = $category->metatag?->description_template;
                    $action->title_template = $category->metatag?->title_template;
                } else {
                    $action->description = $template->convert($this->description_template, $templateValues);
                    $action->title = $template->convert($this->title_template, $templateValues);
                    $category->header = $template->convert($this->header_template, $templateValues);
                    $action->description_template = $this->description_template;
                    $action->title_template = $this->title_template;
                    $category->header_template = $this->header_template;
                }

                $action->keywords = $category->metatag?->keywords;
                $action->id = $category->metatag_id ?: null;
                $metatagId = $action->run()->id;
                $category->metatag_id = $metatagId;

                try {
                    $category->save();

                    if ($read) {
                        $read();
                    }
                } catch (Throwable $error) {
                    $this->addError('Ошибка генерации метатэгов для категории ' . $category->name . ': ' . $error->getMessage());
                }
            }
        }
    }
}
