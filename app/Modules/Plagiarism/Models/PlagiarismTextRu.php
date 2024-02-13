<?php
/**
 * Система проверки плагиата.
 * Пакет содержит классы для проведения анализа на наличие плагиата.
 *
 * @package App.Models.Plagiarism
 */

namespace App\Modules\Plagiarism\Models;

use App\Models\Exceptions\PaymentException;
use App\Models\Exceptions\ProcessingException;
use App\Models\Exceptions\ResponseException;
use App\Modules\Plagiarism\Contracts\Plagiarism;
use App\Modules\Plagiarism\Exceptions\TextShortException;
use App\Modules\Plagiarism\Values\Quality;
use Config;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

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
     * @throws TextShortException
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
            } else if ($response['error_code'] === 112) {
                throw new TextShortException($response['error_desc']);
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
     * @return Quality Готовый анализ.
     * @throws ProcessingException
     * @throws ResponseException
     * @throws GuzzleException
     */
    public function result(string $id): Quality
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

        return new Quality($response['unique'], $seo['water_percent'], $seo['spam_percent']);
    }
}
