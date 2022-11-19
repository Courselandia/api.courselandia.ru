<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Actions\Admin;

use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
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
     * @return FaqEntity Вернет результаты исполнения.
     * @throws RecordNotExistException|ParameterInvalidException
     */
    public function run(): FaqEntity
    {
        $action = app(FaqGetAction::class);
        $action->id = $this->id;
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
