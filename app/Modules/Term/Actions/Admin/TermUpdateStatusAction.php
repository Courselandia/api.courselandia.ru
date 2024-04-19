<?php
/**
 * Модуль Термином.
 * Этот модуль содержит все классы для работы с терминами.
 *
 * @package App\Modules\Term
 */

namespace App\Modules\Term\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Term\Entities\Term as TermEntity;
use App\Modules\Term\Models\Term;
use Cache;

/**
 * Класс действия для обновления статуса термина.
 */
class TermUpdateStatusAction extends Action
{
    /**
     * ID термина.
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
     * @param int|string $id ID термина.
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
     * @return TermEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): TermEntity
    {
        $action = new TermGetAction($this->id);
        $termEntity = $action->run();

        if ($termEntity) {
            $termEntity->status = $this->status;
            Term::find($this->id)->update($termEntity->toArray());
            Cache::tags(['term'])->flush();

            return $termEntity;
        }

        throw new RecordNotExistException(
            trans('term::actions.admin.termUpdateStatusAction.notExistTerm')
        );
    }
}
