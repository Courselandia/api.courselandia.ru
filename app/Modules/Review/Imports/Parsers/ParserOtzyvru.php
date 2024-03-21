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
 * Парсер для otzyvru.com
 */
class ParserOtzyvru extends Parser
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
            sleep(10);

            $total = (int)$driver->findElement(WebDriverBy::cssSelector('.inav-badge.inav-badge-green'))->getText();
            $perPage = 30;
            $buttons = ceil($total / $perPage) - 1;

            for ($i = 0; $i < $buttons; $i++) {
                $buttonAdv = $driver->findElements(WebDriverBy::cssSelector('#close_Label_BOTTOM'));

                if (isset($buttonAdv[0])) {
                    $buttonAdv[0]->click();
                    sleep(2);
                }

                $button = $driver->findElement(WebDriverBy::cssSelector('.my_pagination .btn.blue'));
                $button->click();
                sleep(10);
            }

            $reviews = $driver->findElements(WebDriverBy::cssSelector('.commentbox'));

            foreach ($reviews as $review) {
                try {
                    $title = $review->findElements(WebDriverBy::cssSelector('H2 SPAN[itemprop="name"]'));

                    if (count($title)) {
                        $title = $title[0]->getText();
                    } else {
                        $title = null;
                    }

                    $snippetLinks = $review->findElements(WebDriverBy::cssSelector('.review-snippet A'));

                    if (count($snippetLinks)) {
                        $snippetLinks[0]->click();
                    }

                    $name = $review->findElement(WebDriverBy::cssSelector('INS SPAN[itemprop="name"]'))->getText();
                    $date = $review->findElement(WebDriverBy::cssSelector('.dtreviewed SPAN'))->getAttribute('title');
                    $text = $review->findElement(WebDriverBy::cssSelector('[itemprop="reviewBody"]'))->getText();
                    $rating = $review->findElement(WebDriverBy::cssSelector('.star_ring SPAN'))->getAttribute('style');

                    if ($rating === 'width:65px;') {
                        $rating = 5;
                    } else if ($rating === 'width:52px;') {
                        $rating = 4;
                    } else if ($rating === 'width:39px;') {
                        $rating = 3;
                    } else if ($rating === 'width:26px;') {
                        $rating = 2;
                    } else if ($rating === 'width:13px;') {
                        $rating = 1;
                    } else {
                        $rating = null;
                    }

                    $review = new ParserReview();
                    $review->title = $title;
                    $review->rating = $rating;
                    $review->date = Carbon::createFromFormat('Y-m-d H:i:s', $date . ' 00:00:00');
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
