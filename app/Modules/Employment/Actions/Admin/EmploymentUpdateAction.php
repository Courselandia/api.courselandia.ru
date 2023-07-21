<?php
/**
 * Модуль Трудоустройство.
 * Этот модуль содержит все классы для работы с трудоустройствами.
 *
 * @package App\Modules\Employment
 */

namespace App\Modules\Employment\Actions\Admin;

use Cache;
use Typography;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Employment\Entities\Employment as EmploymentEntity;
use App\Modules\Employment\Models\Employment;

/**
 * Класс действия для обновления трудоустройства.
 */
class EmploymentUpdateAction extends Action
{
    /**
     * ID трудоустройства.
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
     * Статья.
     *
     * @var string|null
     */
    public ?string $text = null;

    /**
     * Статус.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Метод запуска логики.
     *
     * @return EmploymentEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     */
    public function run(): EmploymentEntity
    {
        $action = app(EmploymentGetAction::class);
        $action->id = $this->id;
        $employmentEntity = $action->run();

        if ($employmentEntity) {
            $employmentEntity->id = $this->id;
            $employmentEntity->name = Typography::process($this->name, true);
            $employmentEntity->text = Typography::process($this->text);
            $employmentEntity->status = $this->status;

            Employment::find($this->id)->update($employmentEntity->toArray());
            Cache::tags(['catalog', 'employment'])->flush();

            $action = app(EmploymentGetAction::class);
            $action->id = $this->id;

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('employment::actions.admin.employmentUpdateAction.notExistEmployment')
        );
    }
}
