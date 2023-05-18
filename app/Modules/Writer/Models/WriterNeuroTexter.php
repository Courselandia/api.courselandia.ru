<?php
/**
 * Искусственный интеллект писатель.
 * Пакет содержит классы для написания текстов с использованием искусственного интеллекта.
 *
 * @package App.Models.Writer
 */

namespace App\Modules\Writer\Models;

use App\Models\Exceptions\PaymentException;
use Config;
use Throwable;
use GuzzleHttp\Client;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\ProcessingException;
use App\Modules\Writer\Contracts\Writer;
use App\Models\Exceptions\ResponseException;
use App\Models\Exceptions\RecordNotExistException;

/**
 * Классы драйвер для написания текстов с использованием neuro-texter.ru.
 */
class WriterNeuroTexter extends Writer
{
    /**
     * Запрос на написание текста.
     *
     * @param string $request Запрос на написания текста.
     *
     * @return string ID задачи на генерацию.
     * @throws ResponseException
     * @throws PaymentException
     */
    public function write(string $request): string
    {
        $client = new Client();

        try {
            $response = $client->post(
                'https://neuro-texter.ru/api/freestyle',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . Config::get('writer.services.neuroTexter.token'),
                    ],
                    'json' => [
                        'text' => $request,
                    ],
                ],
            );
        } catch (Throwable $error) {
            if ($error->getCode() === 403) {
                throw new PaymentException($error->getMessage());
            } else {
                throw new ResponseException($error->getMessage());
            }
        }

        $body = $response->getBody();
        $response = json_decode((string)$body, true);

        return $response['task_id'];
    }

    /**
     * Получить результат.
     *
     * @param string $id ID задачи.
     *
     * @return string Готовый текст.
     * @throws ProcessingException
     * @throws ResponseException
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     */
    public function result(string $id): string
    {
        $client = new Client();

        try {
            $response = $client->get(
                'https://neuro-texter.ru/api/task/' . $id,
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . Config::get('writer.services.neuroTexter.token'),
                    ],
                ]
            );
        } catch (Throwable $error) {
            if ($error->getCode() === 404) {
                throw new RecordNotExistException(trans('writer::models.writerNeuroTexter.notExist'));
            } else {
                throw new ResponseException($error->getMessage());
            }
        }

        $body = $response->getBody();
        $response = json_decode((string)$body, true);

        if ($response['status'] === 'ready') {
            return $response['text'];
        } elseif ($response['status'] === 'processing') {
            throw new ProcessingException(trans('writer::models.writerNeuroTexter.processing'));
        } else {
            throw new ParameterInvalidException(trans('writer::models.writerNeuroTexter.failed'));
        }
    }
}
