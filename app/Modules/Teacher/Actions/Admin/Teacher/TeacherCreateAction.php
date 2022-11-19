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
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Teacher\Entities\Teacher as TeacherEntity;
use App\Modules\Teacher\Models\Teacher;
use Cache;
use Illuminate\Http\UploadedFile;
use ReflectionException;

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
     * Описание.
     *
     * @var string|null
     */
    public ?string $description = null;

    /**
     * Ключевые слова.
     *
     * @var string|null
     */
    public ?string $keywords = null;

    /**
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $title = null;

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
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function run(): TeacherEntity
    {
        $action = app(MetatagSetAction::class);
        $action->description = $this->description;
        $action->keywords = $this->keywords;
        $action->title = $this->title;
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
