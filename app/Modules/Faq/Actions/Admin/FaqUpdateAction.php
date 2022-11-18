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
 * Класс действия для обновления FAQ.
 */
class FaqUpdateAction extends Action
{
    /**
     * ID FAQ.
     *
     * @var int|string|null
     */
    public int|string|null $id = null;

    /**
     * ID школы.
     *
     * @var int|null
     */
    public ?int $school_id = null;

    /**
     * Вопрос.
     *
     * @var string|null
     */
    public ?string $question = null;

    /**
     * Ответ.
     *
     * @var string|null
     */
    public ?string $answer = null;

    /**
     * Статус.
     *
     * @var boolean|null
     */
    public ?bool $status = null;

    /**
     * Метод запуска логики.
     *
     * @return FaqEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     */
    public function run(): FaqEntity
    {
        $action = app(FaqGetAction::class);
        $action->id = $this->id;
        $faqEntity = $action->run();

        if ($faqEntity) {
            $faqEntity->id = $this->id;
            $faqEntity->school_id = $this->school_id;
            $faqEntity->question = $this->question;
            $faqEntity->answer = $this->answer;
            $faqEntity->status = $this->status;

            Faq::find($this->id)->update($faqEntity->toArray());
            Cache::tags(['catalog', 'school', 'faq'])->flush();

            $action = app(FaqGetAction::class);
            $action->id = $this->id;

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('faq::actions.admin.faqUpdateAction.notExistFaq')
        );
    }
}
