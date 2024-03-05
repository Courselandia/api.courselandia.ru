<?php
/**
 * Модуль Школ.
 * Этот модуль содержит все классы для работы со школами.
 *
 * @package App\Modules\School
 */

namespace App\Modules\School\Actions\Admin\School;

use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\School\Entities\School as SchoolEntity;
use App\Modules\School\Models\School;
use Cache;

/**
 * Класс действия для обновления статуса школ.
 */
class SchoolUpdateStatusAction extends Action
{
    /**
     * ID школы.
     *
     * @var int|string
     */
    private int|string $id;

    /**
     * Статус.
     *
     * @var bool
     */
    private bool $status;

    /**
     * @param int|string $id ID школы.
     * @param bool $status Статус.
     */
    public function __construct(int|string $id, bool $status)
    {
        $this->id = $id;
        $this->status = $status;
    }

    /**
     * Метод запуска логики.
     *
     * @return SchoolEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): SchoolEntity
    {
        $action = new SchoolGetAction($this->id);
        $schoolEntity = $action->run();

        if ($schoolEntity) {
            $schoolEntity->status = $this->status;
            School::find($this->id)->update($schoolEntity->toArray());
            Cache::tags(['catalog', 'school'])->flush();

            return $schoolEntity;
        }

        throw new RecordNotExistException(
            trans('school::actions.admin.schoolUpdateStatusAction.notExistSchool')
        );
    }
}
