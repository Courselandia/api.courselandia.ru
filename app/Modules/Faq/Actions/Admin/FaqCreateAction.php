<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Actions\Admin;

use Typography;
use App\Models\Action;
use App\Models\Exceptions\ParameterInvalidException;
use App\Modules\Faq\Entities\Faq as FaqEntity;
use App\Modules\Faq\Models\Faq;
use Cache;

/**
 * Класс действия для создания FAQ.
 */
class FaqCreateAction extends Action
{
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
     * @throws ParameterInvalidException
     */
    public function run(): FaqEntity
    {
        $faqEntity = new FaqEntity();
        $faqEntity->school_id = $this->school_id;
        $faqEntity->question = Typography::process($this->question, true);
        $faqEntity->answer = Typography::process($this->answer, true);
        $faqEntity->status = $this->status;

        $faq = Faq::create($faqEntity->toArray());
        Cache::tags(['catalog', 'school', 'faq'])->flush();

        $action = app(FaqGetAction::class);
        $action->id = $faq->id;

        return $action->run();
    }
}
