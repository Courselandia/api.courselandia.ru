<?php
/**
 * Модуль Разделов.
 * Этот модуль содержит все классы для работы с разделами каталога.
 *
 * @package App\Modules\Section
 */

namespace App\Modules\Section\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Section\Entities\Section as SectionEntity;
use App\Modules\Section\Models\Section;
use Cache;

/**
 * Класс действия для обновления статуса раздела.
 */
class SectionUpdateStatusAction extends Action
{
    /**
     * ID раздела.
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
     * @param int|string $id ID раздела.
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
     * @return SectionEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): SectionEntity
    {
        $action = new SectionGetAction($this->id);
        $sectionEntity = $action->run();

        if ($sectionEntity) {
            $sectionEntity->status = $this->status;
            Section::find($this->id)->update($sectionEntity->toArray());
            Cache::tags(['section'])->flush();

            return $sectionEntity;
        }

        throw new RecordNotExistException(
            trans('section::actions.admin.sectionUpdateStatusAction.notExistSection')
        );
    }
}
