<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Imports\Parsers;

use Util;
use Generator;
use Throwable;
use Carbon\Carbon;
use PHPHtmlParser\Dom;
use PHPHtmlParser\Dom\AbstractNode;
use App\Modules\Review\Imports\Parser;
use App\Modules\Review\Entities\ParserReview;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

/**
 * Парсер для Mooc
 */
class ParserMooc extends Parser
{
    /**
     * Путь к API сервиса Mooc.
     *
     * @var string
     */
    private string $urlApi = 'https://mooc.ru/company-load-reviews';

    /**
     * Чтение отзывов.
     *
     * @return Generator<ParserReview>
     */
    public function read(): Generator
    {
        $request = new Request('POST', $this->urlApi, [
            'Accept' => 'application/json, text/javascript, */*; q=0.01',
            'Accept-Language' => 'en-US,en;q=0.5',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Referer' => 'https://mooc.ru/company/',
            'X-Requested-With' => 'XMLHttpRequest',
            'Origin' => 'https://mooc.ru',
            'Connection' => 'keep-alive',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'no-cache',
            'TE' => 'trailers',
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
        ], 'slug=' . $this->getUrl() . '&page=1&limit=10000');

        $client = new Client();

        try {
            $response = $client->send($request)->getBody()->getContents();
            $json = json_decode($response, true);

            $dom = new Dom();
            $dom->loadStr($json['reviews']);
            $reviews = $dom->find('.ReviewItem');

            foreach ($reviews as $review) {
                try {
                    $review = $review->find('.descr')[0];
                    /**
                     * @var AbstractNode $review
                     */
                    $infos = $review->find('.info');
                    $textValue = $review->find('.text')[0]->text;

                    $dateInfo = $infos[1];
                    $textDate = $dateInfo->find('span')[0]->text;
                    $dateValue = str_replace('Дата отзыва ', '', $textDate);
                    $spans = $infos[0]->find('span');
                    $rating = $infos[0]->find('.rating')[0];
                    $authorValue = $spans[0]->text;
                    $titleValue = $spans[2]->text;
                    $ratingValue = $rating->find('.fas')->count();

                    if ($rating->find('.fa-star-half-alt')->count() == 1) {
                        $ratingValue--;
                    }

                    $date = strtotime($dateValue);

                    $review = new ParserReview();
                    $review->title = $titleValue;
                    $review->rating = $ratingValue;
                    $review->date = $date ? Carbon::createFromFormat('U', $date) : Carbon::now();
                    $review->name = $authorValue;
                    $review->review = trim($textValue);

                    yield $review;
                } catch (Throwable $error) {
                    $this->addError($this->getSchool()->getLabel() . ', из: ' . $this->getUrl() . ' : Не удается получить список отзывов. ' . $error->getMessage());
                }
            }
        } catch (Throwable $error) {
            $this->addError($this->getSchool()->getLabel() . ', из: ' . $this->getUrl() . ' : Не удается получить список отзывов. ' . $error->getMessage());
        }
    }

    /**
     * Вернет источник.
     *
     * @return string
     */
    public function getSource(): string
    {
        return 'https://mooc.ru';
    }

    /**
     * Уникальный ключ для проверки уникальности отзыва.
     *
     * @param ParserReview $review Спарсенный отзыв.
     *
     * @return string Ключ.
     */
    public function getUuid(ParserReview $review): string
    {
        return Util::getKey([
            $this->getSchool()->value,
            $review->name,
            $review->title,
            $review->review,
            $review->advantages,
            $review->disadvantages,
            $review->rating,
        ]);
    }
}
