<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

use Exception;
use Illuminate\Contracts\Validation\Validator;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Класс для собственного формирования формата вывода ошибки для запроса.
 */
class FormRequestValidationException extends Exception
{
    /**
     * Запрос.
     *
     * @var FormRequest
     */
    protected FormRequest $response;

    /**
     * Валидатор.
     *
     * @var Validator
     */
    protected Validator $validator;

    /**
     * Код ошибки.
     *
     * @var int
     */
    protected $code = 400;

    /**
     * Конструктор.
     *
     * @param  FormRequest  $response  Запрос.
     * @param  Validator  $validator  Валидатор.
     */
    #[Pure] public function __construct(FormRequest $response, Validator $validator)
    {
        parent::__construct();

        $this->response = $response;
        $this->validator = $validator;
    }

    /**
     * Рендеринг ошибки.
     *
     * @return Response
     * @throws Exception
     */
    public function render(): Response
    {
        if ($this->response->acceptsJson()) {
            return response()->json([
                'success' => false,
                'message' => $this->validator->errors()->first()
            ],
                $this->code);
        }

        throw new Exception($this->validator->errors()->first());
    }
}
