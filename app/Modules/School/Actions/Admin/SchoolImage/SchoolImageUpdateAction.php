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
     * @var int|string
     */
    private int|string $id;

    /**
     * Тип изображения.
     *
     * @var string
     */
    private string $type;

    /**
     * Изображение.
     *
     * @var UploadedFile
     */
    private UploadedFile $image;

    /**
     * @param int|string $id ID школы.
     * @param string $type Тип изображения.
     * @param UploadedFile $image Изображение.
     */
    public function __construct(int|string $id, string $type, UploadedFile $image)
    {
        $this->id = $id;
        $this->type = $type;
        $this->image = $image;
    }

    /**
     * Метод запуска логики.
     *
     * @return SchoolEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): SchoolEntity
    {
        if ($this->id) {
            $action = new SchoolGetAction($this->id);
            $school = $action->run();

            if ($school) {
                $data = $school->toArray();

                if ($this->type === 'logo') {
                    $data['image_logo_id'] = $this->image;
                }

                if ($this->type === 'site') {
                    $data['image_site_id'] = $this->image;
                }

                School::find($this->id)->update($data);
                Cache::tags(['catalog', 'school'])->flush();

                return $action->run();
            }
        }

        throw new RecordNotExistException(trans('access::actions.admin.schoolImageUpdateAction.notExistSchool'));
    }
}
