<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Imports\Parsers;

use Carbon\Carbon;
use Generator;
use Throwable;
use App\Modules\Review\Imports\Parser;
use Facebook\WebDriver\WebDriverBy;
use App\Modules\Review\Imports\Browser;
use App\Modules\Review\Entities\ParserReview;

/**
 * Парсер для spr.ru
 */
class ParserSpr extends Parser
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
            $buttonMore = $driver->findElements(WebDriverBy::cssSelector('[data-loadreviewslist]'));

            if (count($buttonMore)) {
                $buttonMore[0]->click();
                sleep(5);
            }

            $script = '
                const perPage = 10;
                let totalPositive = document.querySelector(".reviewsListPositive .listReviewsQuantity").innerHTML;
                let totalNegative = document.querySelector(".reviewsListNegative .listReviewsQuantity").innerHTML;
                totalPositive = parseInt(totalPositive);
                totalNegative = parseInt(totalNegative);
                const total = totalPositive + totalNegative - 20 * 2;
                let scrolls = Math.ceil(total / perPage);

                for (var i = 0; i < scrolls; i++) {
                    window.setTimeout(function () {
                        const elements = document.getElementsByTagName("BODY");
                        const container = elements[0].children[2];
                        container.scrollIntoView({block: "end", behavior: "smooth"});
                        console.log("Scrolling...");
                    }, 3000 * i);
                }
            ';

            $perPage = 10;
            $totalPositive = (int)$driver->findElement(WebDriverBy::cssSelector('.reviewsListPositive .listReviewsQuantity'))->getText();
            $totalNegative = (int)$driver->findElement(WebDriverBy::cssSelector('.reviewsListNegative .listReviewsQuantity'))->getText();
            $total = $totalPositive + $totalNegative - 20 * 2;
            $scrolls = ceil($total / $perPage);
            $seconds = $scrolls * 3;
            $driver->executeScript($script);
            sleep($seconds);

            $reviews = $driver->findElements(WebDriverBy::cssSelector('.review'));

            foreach ($reviews as $review) {
                try {
                    $rating = null;
                    $title = $review->findElement(WebDriverBy::cssSelector('.reviewTitleText SPAN'))->getText();
                    $name = $review->findElement(WebDriverBy::cssSelector('.reviewAuthor'))->getText();
                    $date = $review->findElement(WebDriverBy::cssSelector('.reviewDate'))->getText();
                    $date = Carbon::createFromFormat('j.m.Y H:i:s', $date . ' 00:00:00');

                    $more = $review->findElements(WebDriverBy::cssSelector('.readMoreReview'));

                    if (count($more)) {
                        $more[0]->click();
                        sleep(2);
                        $text = $driver->findElement(WebDriverBy::cssSelector('.popupReviewsText'))->getText();

                        $close = $driver->findElement(WebDriverBy::cssSelector('#closePopupImg'));
                        $close->click();
                    } else {
                        $text = $review->findElement(WebDriverBy::cssSelector('.reviewText'))->getText();
                    }

                    $review = new ParserReview();
                    $review->title = $title;
                    $review->rating = $rating;
                    $review->date = $date;
                    $review->name = $name;
                    $review->review = $text;

                    yield $review;
                } catch (Throwable $error) {
                    $this->addError($this->getSchool()->getLabel() . ', из: ' . $this->getUrl() . ' : Не удается получить отзыв. ' . $error->getMessage());
                }
            }
        } catch (Throwable $error) {
            $this->addError($this->getSchool()->getLabel() . ', из: ' . $this->getUrl() . ' : Не удается получить список отзывов. ' . $error->getMessage());
        }
    }
}
