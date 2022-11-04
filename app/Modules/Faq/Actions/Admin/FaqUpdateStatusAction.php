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
use App\Modules\Faq\Repositories\Faq;
use Cache;
use ReflectionException;

/**
 * Класс действия для обновления статуса FAQ's.
 */
class FaqUpdateStatusAction extends Action
{
    /**
     * Репозиторий FAQ's.
     *
     * @var Faq
     */
    private Faq $category;

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
     * Конструктор.
     *
     * @param  Faq  $category  Репозиторий FAQ's.
     */
    public function __construct(Faq $category)
    {
        $this->category = $category;
    }

    /**
     * Метод запуска логики.
     *
     * @return FaqEntity Вернет результаты исполнения.
     * @throws RecordNotExistException|ParameterInvalidException|ReflectionException
     */
    public function run(): FaqEntity
    {
        $action = app(FaqGetAction::class);
        $action->id = $this->id;
        $categoryEntity = $action->run();

        if ($categoryEntity) {
            $categoryEntity->status = $this->status;
            $this->category->update($this->id, $categoryEntity);
            Cache::tags(['catalog', 'category', 'direction', 'profession'])->flush();

            return $categoryEntity;
        }

        throw new RecordNotExistException(
            trans('category::actions.admin.categoryUpdateStatusAction.notExistFaq')
        );
    }
}
