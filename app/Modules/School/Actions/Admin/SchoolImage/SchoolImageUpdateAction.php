<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Actions\Admin\SchoolImage;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\School\Actions\Admin\School\SchoolGetAction;
use App\Modules\School\Entities\School as SchoolEntity;
use App\Modules\School\Repositories\School;
use Cache;
use Illuminate\Http\UploadedFile;
use ReflectionException;

/**
 * Обновление изображения школы.
 */
class SchoolImageUpdateAction extends Action
{
    /**
     * Репозиторий школы.
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
     * Тип изображения.
     *
     * @var string|null
     */
    public string|null $type = null;

    /**
     * Изображение.
     *
     * @var UploadedFile|null
     */
    public ?UploadedFile $image = null;

    /**
     * Конструктор.
     *
     * @param  School  $school  Репозиторий школы.
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
        if ($this->id) {
            $action = app(SchoolGetAction::class);
            $action->id = $this->id;
            $school = $action->run();

            if ($school) {
                if ($this->type === 'logo') {
                    $school->image_logo_id = $this->image;
                }

                if ($this->type === 'site') {
                    $school->image_site_id = $this->image;
                }

                $this->school->update($this->id, $school);
                Cache::tags(['catalog', 'school'])->flush();

                return $action->run();
            }
        }

        throw new RecordNotExistException(trans('access::actions.admin.schoolImageUpdateAction.notExistSchool'));
    }
}
