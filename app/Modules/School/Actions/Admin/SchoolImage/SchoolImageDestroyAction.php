<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Actions\Admin\SchoolImage;

use App\Models\Action;
use App\Models\Enums\CacheTime;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\School\Models\School;
use Cache;
use ImageStore;
use ReflectionException;
use Util;

/**
 * Класс действия для удаления изображения школы.
 */
class SchoolImageDestroyAction extends Action
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
     * @param int|string $id ID школы.
     * @param string $type Тип изображения.
     */
    public function __construct(int|string $id, string $type)
    {
        $this->id = $id;
        $this->type = $type;
    }

    /**
     * Метод запуска логики.
     *
     * @return bool Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): bool
    {
        $cacheKey = Util::getKey('school', 'model', $this->id);

        $school = Cache::tags(['catalog', 'school', 'faq'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () {
                return School::find($this->id);
            }
        );

        if ($school) {
            if ($this->type === 'logo') {
                if ($school->image_logo_id) {
                    ImageStore::destroy($school->image_logo_id->id);
                }

                $school->image_logo_id = null;
            }

            if ($this->type === 'site') {
                if ($school->image_site_id) {
                    ImageStore::destroy($school->image_site_id->id);
                }

                $school->image_site_id = null;
            }

            $school->save();
            Cache::tags(['catalog', 'school', 'teacher', 'faq'])->flush();

            return true;
        }

        throw new RecordNotExistException(
            trans('school::actions.admin.schoolImageDestroyAction.notExistSchool')
        );
    }
}
