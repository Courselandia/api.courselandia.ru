<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Actions\Admin\SchoolImage;

use Cache;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\School\Actions\Admin\School\SchoolGetAction;
use App\Modules\School\Entities\School as SchoolEntity;
use App\Modules\School\Models\School;
use Illuminate\Http\UploadedFile;

/**
 * Обновление изображения школы.
 */
class SchoolImageUpdateAction extends Action
{
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
     * Метод запуска логики.
     *
     * @return SchoolEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
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

                School::find($this->id)->update($school->toArray());
                Cache::tags(['catalog', 'school', 'teacher', 'faq'])->flush();

                return $action->run();
            }
        }

        throw new RecordNotExistException(trans('access::actions.admin.schoolImageUpdateAction.notExistSchool'));
    }
}
