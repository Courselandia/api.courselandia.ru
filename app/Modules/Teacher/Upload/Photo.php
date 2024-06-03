<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Upload;

use App\Models\Error;
use Throwable;
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
    use Error;

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
        $this->offLimits();
        $this->upload();
    }

    /**
     * Отключение лимитов.
     *
     * @return void
     */
    private function offLimits(): void
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        ignore_user_abort(true);
    }

    /**
     * Загрузка фотографий.
     *
     * @return void
     * @throws GuzzleException
     * @throws ResponseException
     */
    private function upload(): void
    {
        $items = $this->getItems();

        foreach ($items as $item) {
            if ($item['photo']) {
                $parse = parse_url($item['photo']['origin']);

                if (isset($parse['host'])) {
                    $urlToImage = $item['photo']['origin'];
                } else {
                    $urlToImage = self::DOMAIN . $item['photo']['origin'];
                }

                $ext = pathinfo($urlToImage, PATHINFO_EXTENSION);
                $dir = 'teachers/';
                $nameFile = $dir . $item['slug'];
                $path = $nameFile . '.' . $ext;

                if ($ext === 'webp') {
                    $path = $nameFile . '.jpeg';
                }

                if (!Storage::disk('local')->exists($path)) {
                    try {
                        if ($ext === 'webp') {
                            $im = imagecreatefromwebp($urlToImage);
                            Storage::disk('local')->put($path, '');
                            imagejpeg($im, Storage::disk('local')->path($path));
                        } else {
                            $bites = file_get_contents($urlToImage);
                            Storage::disk('local')->put($path, $bites);
                        }
                    } catch (Throwable $error) {
                        $this->addError($error);
                    }
                }

                $this->fireEvent(
                    'put',
                    [
                        $path,
                    ],
                );
            }
        }
    }

    /**
     * Получение количества учителей.
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
