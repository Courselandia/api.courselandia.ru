<?php
/**
 * Система проверки плагиата.
 * Пакет содержит классы для проведения анализа на наличие плагиата.
 *
 * @package App.Models.Plagiarism
 */

namespace App\Modules\Plagiarism\Models;

use Config;
use GuzzleHttp\Client;
use App\Models\Exceptions\PaymentException;
use GuzzleHttp\Exception\GuzzleException;
use App\Models\Exceptions\ProcessingException;
use App\Modules\Plagiarism\Contracts\Plagiarism;
use App\Models\Exceptions\ResponseException;
use GuzzleHttp\Exception\ClientException;
use App\Modules\Plagiarism\Entities\Result;

/**
 * Классы драйвер для анализа текстов с использованием text.ru.
 */
class PlagiarismTextRu extends Plagiarism
{
    /**
     * Запрос на проведения анализа.
     *
     * @param string $text Текст для проведения анализа.
     *
     * @return string ID задачи на анализ.
     * @throws ResponseException
     * @throws PaymentException|GuzzleException
     */
    public function request(string $text): string
    {
        $client = new Client();

        try {
            $response = $client->post(
                'https://api.text.ru/post',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'json' => [
                        'userkey' => Config::get('plagiarism.services.textRu.token'),
                        'text' => $text,
                        'exceptdomain' => 'courselandia.ru',
                    ],
                ],
            );
        } catch (ClientException $error) {
            throw new ResponseException($error->getMessage());
        }

        $body = $response->getBody();
        $response = json_decode((string)$body, true);

        if (isset($response['error_code'])) {
            if ($response['error_code'] === 142) {
                throw new PaymentException($response['error_desc']);
            } else {
                throw new ResponseException($response['error_desc']);
            }
        }

        return $response['text_uid'];
    }

    /**
     * Получить результат.
     *
     * @param string $id ID задачи.
     *
     * @return Result Готовый анализ.
     * @throws ProcessingException
     * @throws ResponseException
     * @throws GuzzleException
     */
    public function result(string $id): Result
    {
        $client = new Client();

        try {
            $response = $client->get(
                'https://api.text.ru/post',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                    'json' => [
                        'userkey' => Config::get('plagiarism.services.textRu.token'),
                        'uid' => $id,
                        'jsonvisible' => 'detail',
                    ],
                ],
            );
        } catch (ClientException $error) {
            throw new ResponseException($error->getMessage());
        }

        $body = $response->getBody();
        $response = json_decode((string)$body, true);

        if (isset($response['error_code'])) {
            if ($response['error_code'] === 181) {
                throw new ProcessingException(trans('plagiarism::models.plagiarismTextRu.processing'));
            } else {
                throw new ResponseException($response['error_desc']);
            }
        }

        $seo = json_decode($response['seo_check'], true);

        $result = new Result();
        $result->unique = $response['unique'];
        $result->water = $seo['water_percent'];
        $result->spam = $seo['spam_percent'];

        return $result;
    }
}
