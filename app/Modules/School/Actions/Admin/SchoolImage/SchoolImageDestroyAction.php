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
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\School\Repositories\School;
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
     * Тип изображения.
     *
     * @var string|null
     */
    public string|null $type = null;

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
     * @return bool Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException|ReflectionException
     */
    public function run(): bool
    {
        $query = new RepositoryQueryBuilder($this->id);
        $cacheKey = Util::getKey('school', $query);

        $school = Cache::tags(['catalog', 'school', 'faq'])->remember(
            $cacheKey,
            CacheTime::GENERAL->value,
            function () use ($query) {
                return $this->school->get($query);
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

            $this->school->update($this->id, $school);
            Cache::tags(['catalog', 'school', 'teacher', 'faq'])->flush();

            return true;
        }

        throw new RecordNotExistException(
            trans('school::actions.admin.schoolImageDestroyAction.notExistSchool')
        );
    }
}
