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

/**
 * Класс действия для обновления учителя.
 */
class TeacherUpdateAction extends Action
{
    /**
     * ID учителя.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

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
     */
    public function run(): TeacherEntity
    {
        $action = app(TeacherGetAction::class);
        $action->id = $this->id;
        $teacherEntity = $action->run();

        if ($teacherEntity) {
            $action = app(MetatagSetAction::class);
            $action->description = $this->description;
            $action->keywords = $this->keywords;
            $action->title = $this->title;
            $action->id = $teacherEntity->metatag_id;
            $metatag = $action->run();

            $teacherEntity->metatag_id = $metatag->id;
            $teacherEntity->name = $this->name;
            $teacherEntity->link = $this->link;
            $teacherEntity->text = $this->text;
            $teacherEntity->rating = $this->rating;
            $teacherEntity->status = $this->status;

            if ($this->image) {
                $teacherEntity->image_middle_id = $this->image;
                $teacherEntity->image_small_id = $this->image;
            }

            $teacher = Teacher::find($this->id);
            $teacher->update($teacherEntity->toArray());
            $teacher->directions()->sync($this->directions ?: []);
            $teacher->schools()->sync($this->schools ?: []);

            Cache::tags(['catalog', 'teacher', 'direction', 'school'])->flush();

            $action = app(TeacherGetAction::class);
            $action->id = $this->id;
            return $action->run();
        }

        throw new RecordNotExistException(
            trans('teacher::actions.admin.teacherUpdateAction.notExistTeacher')
        );
    }
}
