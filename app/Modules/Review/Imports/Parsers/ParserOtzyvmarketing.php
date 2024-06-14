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
 * Парсер для otzyvmarketing.ru
 */
class ParserOtzyvmarketing extends Parser
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

            $paginations = $driver->findElements(WebDriverBy::cssSelector('.pagination LI:last-child A'));

            if (count($paginations)) {
                do {
                    $paginationButtonLast = $driver->findElement(WebDriverBy::cssSelector('.pagination LI:last-child A'));

                    if (!$paginationButtonLast->getText()) {
                        $paginationButtonLast = $driver->findElements(WebDriverBy::cssSelector('.pagination LI A'));
                        $paginationCountButtons = count($paginationButtonLast);
                        $paginationButtonBeforeLast = $paginationButtonLast[$paginationCountButtons - 1];
                        $paginationButtonBeforeLast->click();
                        sleep(2);
                        $nextClick = true;
                    } else {
                        $nextClick = false;
                    }
                } while ($nextClick === true);

                $total = $driver->findElement(WebDriverBy::cssSelector('.pagination LI:last-child A'))->getText();
            } else {
                $total = 1;
            }

            for ($page = 1; $page <= $total; $page++) {
                $driver->get($this->getUrl() . '/?page=' . $page);
                sleep(5);

                $reviews = $driver->findElements(WebDriverBy::cssSelector('.review_item'));

                foreach ($reviews as $review) {
                    try {
                        $title = $review->findElement(WebDriverBy::cssSelector('.text .review-title A'))->getText();
                        $name = $review->findElement(WebDriverBy::cssSelector('SPAN[itemprop="author"] SPAN[itemprop="name"]'))->getText();
                        $text = $review->findElement(WebDriverBy::cssSelector('P[itemprop="description"]'))->getText();
                        $date = $review->findElement(WebDriverBy::cssSelector('.date'))->getText();
                        $date = Carbon::createFromFormat('d.m.Y в H:i:s', $date . ':00');

                        $ratings = $review->findElements(WebDriverBy::cssSelector('.stages_inner'));
                        $rating = null;

                        if (count($ratings)) {
                            $rating = $ratings[0]->getAttribute('style');

                            if ($rating === 'width:100px;') {
                                $rating = 5;
                            } else if ($rating === 'width:80px;') {
                                $rating = 4;
                            } else if ($rating === 'width:60px;') {
                                $rating = 3;
                            } else if ($rating === 'width:40px;') {
                                $rating = 2;
                            } else if ($rating === 'width:20%;') {
                                $rating = 1;
                            } else {
                                $rating = null;
                            }
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
            }
        } catch (Throwable $error) {
            $this->addError($this->getSchool()->getLabel() . ', из: ' . $this->getUrl() . ' : Не удается получить список отзывов. ' . $error->getMessage());
        }
    }
}
