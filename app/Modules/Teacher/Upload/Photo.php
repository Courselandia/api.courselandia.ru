<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Upload;

use Storage;
use App\Models\Event;
use GuzzleHttp\Client;
use App\Models\Exceptions\ResponseException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Грабинг фото учителей.
 */
class Photo
{
    use Event;

    /**
     * Домен.
     *
     * @var string
     */
    private const DOMAIN = 'https://api.academy-market.com';

    /**
     * Запуск процесса.
     *
     * @throws ResponseException|GuzzleException
     */
    public function run(): void
    {
        $items = $this->getItems();

        foreach ($items as $item) {
            if ($item['photo']) {
                $urlToImage = self::DOMAIN . $item['photo']['origin'];

                $ext = pathinfo($urlToImage, PATHINFO_EXTENSION);
                $nameFile = 'teachers/' . $item['slug'];
                $path = $nameFile . '.' . $ext;

                if (!Storage::disk('local')->exists($path)) {
                    if ($ext === 'webp') {
                        $im = imagecreatefromwebp($urlToImage);
                        $path = $nameFile . '.jpeg';
                        Storage::disk('local')->put($path, '');
                        imagejpeg($im, Storage::disk('local')->path($path));
                    } else {
                        $bites = file_get_contents($urlToImage);
                        Storage::disk('local')->put('/teachers/' . $item['slug'] . '.' . $ext, $bites);
                    }
                }

                $this->fireEvent(
                    'put',
                    [
                        $path
                    ]
                );
            }
        }
    }

    /**
     * Получение количеста учителей.
     *
     * @return int Количество учителей.
     * @throws GuzzleException
     * @throws ResponseException
     */
    public function getCount(): int
    {
        return count($this->getItems());
    }

    /**
     * Получение массива учителей.
     *
     * @return array Массив учителей.
     * @throws GuzzleException
     * @throws ResponseException
     */
    private function getItems(): array
    {
        $client = new Client();

        try {
            $response = $client->get(
                self::DOMAIN . '/teacher?pageSize=10000',
                [
                    'headers' => [
                        'Content-Type' => 'application/json',
                        'Accept' => 'application/json',
                    ],
                ],
            );
        } catch (ClientException $error) {
            throw new ResponseException($error->getMessage());
        }

        $body = $response->getBody();
        $response = json_decode((string)$body, true);

        return $response['data']['result'];
    }
}
