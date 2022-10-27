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
use App\Modules\School\Entities\School as SchoolEntity;
use App\Modules\School\Repositories\School;
use Cache;
use ReflectionException;

/**
 * Класс действия для обновления статуса школ.
 */
class SchoolUpdateStatusAction extends Action
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
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

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
     * @throws RecordNotExistException|ParameterInvalidException|ReflectionException
     */
    public function run(): SchoolEntity
    {
        $action = app(SchoolGetAction::class);
        $action->id = $this->id;
        $schoolEntity = $action->run();

        if ($schoolEntity) {
            $schoolEntity->status = $this->status;
            $this->school->update($this->id, $schoolEntity);
            Cache::tags(['catalog', 'school'])->flush();

            return $schoolEntity;
        }

        throw new RecordNotExistException(
            trans('school::actions.admin.schoolUpdateStatusAction.notExistSchool')
        );
    }
}
