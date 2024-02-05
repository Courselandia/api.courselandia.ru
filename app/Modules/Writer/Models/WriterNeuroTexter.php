<?php
/**
 * Искусственный интеллект писатель.
 * Пакет содержит классы для написания текстов с использованием искусственного интеллекта.
 *
 * @package App.Models.Writer
 */

namespace App\Modules\Writer\Models;

use App\Models\Exceptions\LimitException;
use Config;
use GuzzleHttp\Client;
use App\Models\Exceptions\PaymentException;
use GuzzleHttp\Exception\GuzzleException;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\ProcessingException;
use App\Modules\Writer\Contracts\Writer;
use App\Models\Exceptions\ResponseException;
use App\Models\Exceptions\RecordNotExistException;
use GuzzleHttp\Exception\ClientException;

/**
 * Классы драйвер для написания текстов с использованием neuro-texter.ru.
 */
class WriterNeuroTexter extends Writer
{
    /**
     * Запрос на написание текста.
     *
     * @param string $request Запрос на написания текста.
     * @param array|null $options Дополнительные опции настройки сети.
     *
     * @return string ID задачи на генерацию.
     * @throws ResponseException
     * @throws PaymentException|GuzzleException
     * @throws LimitException
     */
    public function request(string $request, array $options = null): string
    {
        $client = new Client();

        try {
            $url = 'https://neuro-texter.ru/api/freestyle';

            if (isset($options['rewrite']) && $options['rewrite'] === true) {
                $url = 'https://neuro-texter.ru/api/rewrite';
            }

            $response = $client->post(
                $url,
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer ' . Config::get('writer.services.neuroTexter.token'),
                    ],
                    'json' => [
                        'text' => $request,
                        ... $options ?: [],
                    ],
                ],
            );
        } catch (ClientException $error) {
            $data = json_decode($error->getResponse()->getBody()->getContents(), true);

            if ($error->getCode() === 403) {
                throw new PaymentException($data['error']);
            } elseif ($error->getCode() === 400) {
                throw new LimitException($data['error']);
            } else {
                throw new ResponseException($data['error']);
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
     * @throws PaymentException|GuzzleException
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
        } catch (ClientException $error) {
            $data = json_decode($error->getResponse()->getBody()->getContents(), true);

            if ($error->getCode() === 404) {
                throw new RecordNotExistException(trans('writer::models.writerNeuroTexter.notExist'));
            } else if ($error->getCode() === 403) {
                throw new PaymentException($data['error']);
            } else {
                throw new ResponseException($data['error']);
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
