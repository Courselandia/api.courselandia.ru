<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Actions\Admin\Teacher;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use App\Modules\Teacher\Entities\Teacher as TeacherEntity;
use App\Modules\Teacher\Models\Teacher;
use Cache;
use Illuminate\Http\UploadedFile;

/**
 * Класс действия для создания учителя.
 */
class TeacherCreateAction extends Action
{
    /**
     * Название.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Ссылка.
     *
     * @var string|null
     */
    public ?string $link = null;

    /**
     * Текст.
     *
     * @var string|null
     */
    public ?string $text = null;

    /**
     * Рейтинг.
     *
     * @var float|null
     */
    public ?float $rating = null;

    /**
     * Изображение.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Шаблон описания.
     *
     * @var string|null
     */
    public ?string $template_description = null;

    /**
     * Ключевые слова.
     *
     * @var string|null
     */
    public ?string $keywords = null;

    /**
     * Шаблон заголовка.
     *
     * @var string|null
     */
    public ?string $template_title = null;

    /**
     * ID направлений.
     *
     * @var int[]
     */
    public ?array $directions = null;

    /**
     * ID школ.
     *
     * @var int[]
     */
    public ?array $schools = null;

    /**
     * Метод запуска логики.
     *
     * @return TeacherEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException
     * @throws TemplateException
     */
    public function run(): TeacherEntity
    {
        $action = app(MetatagSetAction::class);
        $template = new Template();

        $templateValues = [];

        $action->description = $template->convert($this->template_description, $templateValues);
        $action->title = $template->convert($this->template_title, $templateValues);
        $action->template_description = $this->template_description;
        $action->template_title = $this->template_title;
        $action->keywords = $this->keywords;

        $metatag = $action->run();

        $teacherEntity = new TeacherEntity();
        $teacherEntity->name = $this->name;
        $teacherEntity->link = $this->link;
        $teacherEntity->text = $this->text;
        $teacherEntity->rating = $this->rating;
        $teacherEntity->image_small_id = $this->image;
        $teacherEntity->image_middle_id = $this->image;
        $teacherEntity->status = $this->status;
        $teacherEntity->metatag_id = $metatag->id;

        $teacher = Teacher::create($teacherEntity->toArray());
        $teacher->directions()->sync($this->directions ?: []);
        $teacher->schools()->sync($this->schools ?: []);

        Cache::tags(['catalog', 'teacher', 'direction', 'school'])->flush();

        $action = app(TeacherGetAction::class);
        $action->id = $teacher->id;

        return $action->run();
    }
}
