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
use App\Modules\Review\Imports\Parser;
use Facebook\WebDriver\WebDriverBy;
use App\Modules\Review\Imports\Browser;
use App\Modules\Review\Entities\ParserReview;

/**
 * Парсер для skillbox.ru
 */
class ParserSkillbox extends Parser
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

        $script = '
            let count = 0;

            function openAllReviews() {
                const btn = document.querySelector(`.load-more`);

                if (btn) {
                    btn.click();
                    count++;

                    window.setTimeout(openAllReviews, 3000);
                }
            }

            if (count <= 80) {
                openAllReviews();
            }
        ';

        try {
            $driver->get($this->getUrl());
            sleep(15);
            $driver->executeScript($script);
            sleep(250);

            $reviews = $driver->findElements(WebDriverBy::cssSelector('.reviews-list__item'));

            foreach ($reviews as $review) {
                try {
                    $title = null;
                    $rating = 5;
                    $date = null;

                    $name = $review->findElement(WebDriverBy::cssSelector('.ui-text-review-header__author'))->getText();
                    $text = $review->findElement(WebDriverBy::cssSelector('.ui-text-review__text'))->getText();

                    $review = new ParserReview();
                    $review->title = $title;
                    $review->rating = $rating;
                    $review->date = $date;
                    $review->name = $name;
                    $review->review = $text;

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
