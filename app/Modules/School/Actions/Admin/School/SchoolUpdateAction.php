<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Actions\Admin\School;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Image\Entities\Image;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\School\Entities\School as SchoolEntity;
use App\Modules\School\Repositories\School;
use Cache;
use Illuminate\Http\UploadedFile;
use ReflectionException;

/**
 * Класс действия для обновления школ.
 */
class SchoolUpdateAction extends Action
{
    /**
     * Репозиторий школ.
     *
     * @var School
     */
    private School $school;

    /**
     * ID школы.
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
     * Заголовок.
     *
     * @var string|null
     */
    public ?string $header = null;

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
     * Сыылка на сайт.
     *
     * @var string|null
     */
    public ?string $site = null;

    /**
     * Изображение логотипа.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_logo_id = null;

    /**
     * Изображение сайта.
     *
     * @var int|UploadedFile|Image|null
     */
    public int|UploadedFile|Image|null $image_site_id = null;

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
     * Конструктор.
     *
     * @param  School  $school  Репозиторий школ.
     */
    public function __construct(School $school)
    {
        $this->school = $school;
    }

    /**
     * Метод запуска логики.
     *
     * @return SchoolEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @throws ReflectionException
     */
    public function run(): SchoolEntity
    {
        $action = app(SchoolGetAction::class);
        $action->id = $this->id;
        $schoolEntity = $action->run();

        if ($schoolEntity) {
            $action = app(MetatagSetAction::class);
            $action->description = $this->description;
            $action->keywords = $this->keywords;
            $action->title = $this->title;
            $metatag = $action->run();

            $schoolEntity->metatag_id = $metatag->id;
            $schoolEntity->name = $this->name;
            $schoolEntity->header = $this->header;
            $schoolEntity->link = $this->link;
            $schoolEntity->text = $this->text;
            $schoolEntity->site = $this->site;
            $schoolEntity->rating = $this->rating;
            $schoolEntity->status = $this->status;

            if ($this->image_logo_id) {
                $schoolEntity->image_logo_id = $this->image_logo_id;
            }

            if ($this->image_site_id) {
                $schoolEntity->image_site_id = $this->image_site_id;
            }

            $this->school->update($this->id, $schoolEntity);
            Cache::tags(['catalog', 'school', 'teacher', 'review', 'faq'])->flush();

            $action = app(SchoolGetAction::class);
            $action->id = $this->id;
            return $action->run();
        }

        throw new RecordNotExistException(
            trans('school::actions.admin.schoolUpdateAction.notExistSchool')
        );
    }
}
