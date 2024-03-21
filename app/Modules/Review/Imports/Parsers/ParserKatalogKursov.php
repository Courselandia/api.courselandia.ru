<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Imports\Parsers;

use Generator;
use Throwable;
use Carbon\Carbon;
use App\Modules\Review\Imports\Parser;
use Facebook\WebDriver\WebDriverBy;
use App\Modules\Review\Imports\Browser;
use App\Modules\Review\Entities\ParserReview;

/**
 * Парсер для katalog-kursov.ru
 */
class ParserKatalogKursov extends Parser
{
    /**
     * Чтение отзывов.
     *
     * @return Generator<ParserReview>
     */
    public function read(): Generator
    {
        $browser = new Browser();
        $driver = $browser->getDriver();
        $driver->switchTo()->newWindow();

        try {
            $driver->get($this->getUrl());
            sleep(5);
            $reviews = $driver->findElements(WebDriverBy::cssSelector('.single-bts-right-reviews-list .reviews-list-item'));

            foreach ($reviews as $review) {
                try {
                    $name = $review->findElement(WebDriverBy::cssSelector('SPAN[itemprop="name"]'))->getText();
                    $title = $review->findElement(WebDriverBy::cssSelector('H2[itemprop="name"]'))->getText();
                    $text = $review->findElement(WebDriverBy::cssSelector('[itemprop="description"]'))->getText();
                    $date = $review->findElement(WebDriverBy::cssSelector('SPAN[itemprop="datePublished"]'))->getText();
                    $rating = (int)$review->findElement(WebDriverBy::cssSelector('SPAN[itemprop="ratingValue"]'))->getText();
                    $date = str_replace(',', '', $date);

                    $review = new ParserReview();
                    $review->title = $title;
                    $review->rating = $rating;
                    $review->date = $this->getDate($date);
                    $review->name = $name;
                    $review->review = $text;

                    yield $review;
                } catch (Throwable $error) {
                    $this->addError($this->getSchool()->getLabel() . ', из: ' . $this->getUrl() . ' : Не удается получить отзывов. ' . $error->getMessage());
                }
            }
        } catch (Throwable $error) {
            $this->addError($this->getSchool()->getLabel() . ', из: ' . $this->getUrl() . ' : Не удается получить список отзывов. ' . $error->getMessage());
        }
    }

    /**
     * Получение даты.
     *
     * @param string $date Дата в текстовом формате.
     *
     * @return Carbon Дата.
     */
    private function getDate(string $date): Carbon
    {
        $months = [
            ' января ' => '.01.',
            ' февраля ' => '.02.',
            ' марта ' => '.03.',
            ' апреля ' => '.04.',
            ' мая ' => '.05.',
            ' июня ' => '.06.',
            ' июля ' => '.07.',
            ' августа ' => '.08.',
            ' сентября ' => '.09.',
            ' октября ' => '.10.',
            ' ноября ' => '.11.',
            ' декабря ' => '.12.',
        ];

        $date = str_replace(array_keys($months), array_values($months), $date);

        return Carbon::createFromFormat('d.m.Y H:i:s', $date . ' 00:00:00');
    }
}
