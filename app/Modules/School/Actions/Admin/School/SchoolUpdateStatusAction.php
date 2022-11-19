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
     * Метод запуска логики.
     *
     * @return SchoolEntity Вернет результаты исполнения.
     * @throws RecordNotExistException|ParameterInvalidException
     */
    public function run(): SchoolEntity
    {
        $action = app(SchoolGetAction::class);
        $action->id = $this->id;
        $schoolEntity = $action->run();

        if ($schoolEntity) {
            $schoolEntity->status = $this->status;
            School::find($this->id)->update($schoolEntity->toArray());
            Cache::tags(['catalog', 'school', 'teacher', 'review', 'faq'])->flush();

            return $schoolEntity;
        }

        throw new RecordNotExistException(
            trans('school::actions.admin.schoolUpdateStatusAction.notExistSchool')
        );
    }
}
