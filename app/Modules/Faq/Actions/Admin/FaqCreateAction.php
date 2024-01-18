<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Actions\Admin;

use App\Modules\Faq\Data\FaqCreate;
use Typography;
use App\Models\Action;
use App\Modules\Faq\Entities\Faq as FaqEntity;
use App\Modules\Faq\Models\Faq;
use Cache;

/**
 * Класс действия для создания FAQ.
 */
class FaqCreateAction extends Action
{
    /**
     * @var FaqCreate Данные для создания FAQ.
     */
    private FaqCreate $data;

    /**
     * @param FaqCreate $data Данные для создания FAQ.
     */
    public function __construct(FaqCreate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return FaqEntity Вернет результаты исполнения.
     */
    public function run(): FaqEntity
    {
        $faqEntity = FaqEntity::from([
            'school_id' => $this->data->school_id,
            'question' => Typography::process($this->data->question, true),
            'answer' => Typography::process($this->data->answer, true),
            'status' => $this->data->status,
        ]);


        $faq = Faq::create($faqEntity->toArray());
        Cache::tags(['catalog', 'school', 'faq'])->flush();

        $action = new FaqGetAction($faq->id);

        return $action->run();
    }
}
