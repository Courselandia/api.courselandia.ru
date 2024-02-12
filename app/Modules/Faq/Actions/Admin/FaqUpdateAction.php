<?php
/**
 * Модуль FAQ's.
 * Этот модуль содержит все классы для работы с FAQ's.
 *
 * @package App\Modules\Faq
 */

namespace App\Modules\Faq\Actions\Admin;

use App\Modules\Faq\Data\FaqUpdate;
use Typography;
use App\Models\Action;
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
     * Данные для обновления FAQ.
     *
     * @var FaqUpdate
     */
    private FaqUpdate $data;

    /**
     * @param FaqUpdate $data Данные для обновления FAQ.
     */
    public function __construct(FaqUpdate $data)
    {
        $this->data = $data;
    }

    /**
     * Метод запуска логики.
     *
     * @return FaqEntity Вернет результаты исполнения.
     * @throws RecordNotExistException
     */
    public function run(): FaqEntity
    {
        $action = new FaqGetAction($this->data->id);
        $faqEntity = $action->run();

        if ($faqEntity) {
            $faqEntity = FaqEntity::from([
                ...$faqEntity->toArray(),
                ...$this->data->toArray(),
                'question' => Typography::process($this->data->question, true),
                'answer' => Typography::process($this->data->answer, true, false),
            ]);

            Faq::find($this->data->id)->update($faqEntity->toArray());
            Cache::tags(['catalog', 'school', 'faq'])->flush();

            $action = new FaqGetAction($this->data->id);

            return $action->run();
        }

        throw new RecordNotExistException(
            trans('faq::actions.admin.faqUpdateAction.notExistFaq')
        );
    }
}
