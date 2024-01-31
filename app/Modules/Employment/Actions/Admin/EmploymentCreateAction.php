<?php
/**
 * Модуль Трудоустройство.
 * Этот модуль содержит все классы для работы с трудоустройствами.
 *
 * @package App\Modules\Employment
 */

namespace App\Modules\Employment\Actions\Admin;

use App\Modules\Employment\Data\EmploymentCreate;
use Typography;
use App\Models\Action;
use App\Modules\Employment\Entities\Employment as EmploymentEntity;
use App\Modules\Employment\Models\Employment;
use Cache;

/**
 * Класс действия для создания трудоустройства.
 */
class EmploymentCreateAction extends Action
{
    /**
     * Данные для создания трудоустройства.
     *
     * @var EmploymentCreate
     */
    private EmploymentCreate $data;

    /**
     * @param EmploymentCreate $data Данные для создания трудоустройства.
     */
    public function __construct(EmploymentCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return EmploymentEntity Вернет результаты исполнения.
     */
    public function run(): EmploymentEntity
    {
        $employmentEntity = EmploymentEntity::from([
            ...$this->data->toArray(),
            'name' => Typography::process($this->data->name, true),
            'text' => Typography::process($this->data->text),
        ]);

        $employment = Employment::create($employmentEntity->toArray());
        Cache::tags(['catalog', 'employment'])->flush();

        $action = new EmploymentGetAction($employment->id);

        return $action->run();
    }
}
