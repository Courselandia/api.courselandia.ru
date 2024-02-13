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

/**
 * Класс для отправки сообщения через форму обратной связи.
 */
class FeedbackSendAction extends Action
{
    /**
     * Имя.
     *
     * @var string
     */
    private string $name;

    /**
     * E-mail.
     *
     * @var string
     */
    private string $email;

    /**
     * Сообщение.
     *
     * @var string
     */
    private string $message;

    /**
     * Телефон.
     *
     * @var string|null
     */
    private ?string $phone;

    /**
     * @param string $name Имя.
     * @param string $email E-mail.
     * @param string $message Сообщение.
     * @param string|null $phone Телефон.
     */
    public function __construct(
        string $name,
        string $email,
        string $message,
        string $phone = null,
    )
    {
        $this->name = $name;
        $this->email = $email;
        $this->message = $message;
        $this->phone = $phone;
    }

    /**
     * Метод запуска логики.
     *
     * @return FeedbackEntity Вернет результаты исполнения.
     */
    public function run(): FeedbackEntity
    {
        Mail::to(Config::get('mail.admin'))->queue(
            new FeedbackMail($this->name, $this->email, $this->phone, $this->message)
        );

        $feedbackEntity = FeedbackEntity::from([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'message' => Util::getText($this->message),
        ]);

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

        $action = new FeedbackGetAction($feedback->id);

        return $action->run();
    }
}
