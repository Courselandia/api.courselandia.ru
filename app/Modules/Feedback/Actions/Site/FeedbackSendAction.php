<?php
/**
 * Модуль Обратной связи.
 * Этот модуль содержит все классы для работы с обратной связью.
 *
 * @package App\Modules\Feedback
 */

namespace App\Modules\Feedback\Actions\Site;

use Cache;
use Config;
use Mail;
use Alert;
use Util;
use App\Models\Action;
use App\Modules\Feedback\Emails\Feedback as FeedbackMail;
use App\Modules\Feedback\Models\Feedback;
use App\Modules\Feedback\Entities\Feedback as FeedbackEntity;
use App\Modules\Feedback\Actions\Admin\FeedbackGetAction;
use App\Models\Exceptions\ParameterInvalidException;

/**
 * Класс для отправки сообщения через форму обратной связи.
 */
class FeedbackSendAction extends Action
{
    /**
     * Имя.
     *
     * @var string|null
     */
    public ?string $name = null;

    /**
     * E-mail.
     *
     * @var string|null
     */
    public ?string $email = null;

    /**
     * Телефон.
     *
     * @var string|null
     */
    public ?string $phone = null;

    /**
     * Сообщение.
     *
     * @var string|null
     */
    public ?string $message = null;

    /**
     * Метод запуска логики.
     *
     * @return FeedbackEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException
     */
    public function run(): FeedbackEntity
    {
        Mail::to(Config::get('mail.to.address'))->queue(
            new FeedbackMail($this->name, $this->email, $this->phone, $this->message)
        );

        $feedbackEntity = new FeedbackEntity();
        $feedbackEntity->name = $this->name;
        $feedbackEntity->email = $this->email;
        $feedbackEntity->phone = $this->phone;
        $feedbackEntity->message = Util::getText($this->message);

        $feedback = Feedback::create($feedbackEntity->toArray());
        Cache::tags(['feedback'])->flush();

        Alert::add(
            trans('feedback::actions.site.feedbackSendAction.alert', ['id' => $feedback->id]),
            true,
            null,
            '/dashboard/feedbacks/' . $feedback->id,
            'Отправил',
            'green'
        );

        $action = app(FeedbackGetAction::class);
        $action->id = $feedback->id;

        return $action->run();
    }
}
