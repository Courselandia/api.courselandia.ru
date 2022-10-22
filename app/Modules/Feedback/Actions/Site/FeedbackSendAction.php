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
use ReflectionException;
use App\Modules\Feedback\Emails\Feedback as FeedbackMail;
use App\Modules\Feedback\Repositories\Feedback;
use App\Modules\Feedback\Entities\Feedback as FeedbackEntity;
use App\Modules\Feedback\Actions\Admin\FeedbackGetAction;
use App\Models\Exceptions\ParameterInvalidException;

/**
 * Класс для отправки сообщения через форму обратной связи.
 */
class FeedbackSendAction extends Action
{
    /**
     * Репозиторий обратной связи.
     *
     * @var Feedback
     */
    private Feedback $feedback;

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
     * Конструктор.
     *
     * @param  Feedback  $feedback  Репозиторий обратной связи.
     */
    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
    }

    /**
     * Метод запуска логики.
     *
     * @return FeedbackEntity Вернет результаты исполнения.
     * @throws ParameterInvalidException
     * @throws ReflectionException
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

        $id = $this->feedback->create($feedbackEntity);
        Cache::tags(['feedback'])->flush();

        Alert::add(
            trans('feedback::actions.site.feedbackSendAction.alert', ['id' => $id]),
            true,
            null,
            '/dashboard/feedbacks/'.$id,
            'Отправил',
            'green'
        );

        $action = app(FeedbackGetAction::class);
        $action->id = $id;

        return $action->run();
    }
}
