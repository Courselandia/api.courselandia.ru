<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывовами.
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
 * Парсер для tutortop.ru
 */
class ParserTutortop extends Parser
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
            $reviews = $driver->findElements(WebDriverBy::cssSelector('.reviews-list-item'));

            foreach ($reviews as $review) {
                try {
                    $name = $review->findElement(WebDriverBy::cssSelector('span[itemprop="author"]'))->getText();;
                    $title = $review->findElement(WebDriverBy::cssSelector('.list-item-title'))->getText();
                    $rating = $review->findElement(WebDriverBy::cssSelector('.reviews-list-ball'))->getText();
                    $reviewText = $review->findElement(WebDriverBy::cssSelector('.list-item-content'))->getText();
                    $date = $review->findElement(WebDriverBy::cssSelector('span[itemprop="datePublished"]'))->getText();
                    $date = strtotime($date);

                    $review = new ParserReview();
                    $review->title = $title;
                    $review->rating = $rating;
                    $review->date = Carbon::createFromFormat('U', $date);
                    $review->name = $name;
                    $review->review = $reviewText;

                    yield $review;
                } catch (Throwable $error) {
                    $this->addError($this->getSchool()->getLabel() . ', из: ' . $this->getUrl() . ' : Не удается получить список отзывов. ' . $error->getMessage());
                }
            }
        } catch (Throwable $error) {
            $this->addError($this->getSchool()->getLabel() . ', из: ' . $this->getUrl() . ' : Не удается получить список отзывов. ' . $error->getMessage());
        }
    }
}
