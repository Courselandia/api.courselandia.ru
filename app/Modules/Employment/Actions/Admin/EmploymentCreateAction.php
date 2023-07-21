<?php
/**
 * Модуль Трудоустройство.
 * Этот модуль содержит все классы для работы с трудоустройствами.
 *
 * @package App\Modules\Employment
 */

namespace App\Modules\Employment\Actions\Admin;

use Typography;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Employment\Entities\Employment as EmploymentEntity;
use App\Modules\Employment\Models\Employment;
use Cache;

/**
 * Класс действия для создания трудоустройства.
 */
class EmploymentCreateAction extends Action
{
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
     * @throws ParameterInvalidException
     */
    public function run(): EmploymentEntity
    {
        $employmentEntity = new EmploymentEntity();
        $employmentEntity->name = Typography::process($this->name, true);
        $employmentEntity->text = Typography::process($this->text);
        $employmentEntity->status = $this->status;

        $employment = Employment::create($employmentEntity->toArray());
        Cache::tags(['catalog', 'employment'])->flush();

        $action = app(EmploymentGetAction::class);
        $action->id = $employment->id;

        return $action->run();
    }
}
