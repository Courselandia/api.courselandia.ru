<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\RecordNotExistException;
use App\Modules\Faq\Entities\Faq as FaqEntity;
use App\Modules\Faq\Models\Faq;
use Cache;

/**
 * Класс действия для обновления статуса FAQ's.
 */
class FaqUpdateStatusAction extends Action
{
    /**
     * ID FAQ.
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
     * @param int|string $id ID FAQ.
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
     * @return FaqEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): FaqEntity
    {
        $action = new FaqGetAction($this->id);
        $categoryEntity = $action->run();

        if ($categoryEntity) {
            $categoryEntity->status = $this->status;
            Faq::find($this->id)->update($categoryEntity->toArray());

            Cache::tags(['catalog', 'category', 'direction', 'profession'])->flush();

            return $categoryEntity;
        }

        throw new RecordNotExistException(
            trans('category::actions.admin.categoryUpdateStatusAction.notExistFaq')
        );
    }
}
