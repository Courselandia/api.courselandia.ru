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
use App\Modules\Review\Imports\Parser;
use Facebook\WebDriver\WebDriverBy;
use App\Modules\Review\Imports\Browser;
use App\Modules\Review\Entities\ParserReview;

/**
 * Парсер для vk.com
 */
class ParserVk extends Parser
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

            $pageButtonToLast = $driver->findElement(WebDriverBy::cssSelector('.pg_lnk.fl_l:last-child'));
            $pageButtonToLast->click();
            sleep(5);
            $totalPages = (int)$driver->findElement(WebDriverBy::cssSelector('.pg_lnk_sel.fl_l'))->getText();

            $script = '
            for (var i = 0; i < ' . $totalPages . '; i++) {
                window.setTimeout(function () {
                    window.scrollTo(0, document.body.scrollHeight);
                }, 1000 * i);
            }
            ';

            $wait = $totalPages * 1.5;
            $driver->get($this->getUrl());

            sleep(5);
            $driver->executeScript($script);
            sleep($wait);

            $reviews = $driver->findElements(WebDriverBy::cssSelector('.bp_post'));

            foreach ($reviews as $review) {
                try {
                    $name = $review->findElement(WebDriverBy::cssSelector('.bp_author'))->getText();
                    $text = $review->findElement(WebDriverBy::cssSelector('.bp_text'))->getText();
                    $date = $review->findElement(WebDriverBy::cssSelector('.bp_date'))->getText();

                    if ($date) {
                        if (strpos($date, 'yesterday') !== false) {
                            $dateYesterday = date('Y-m-d', strtotime('-1 days'));
                            $date = str_replace('yesterday', $dateYesterday, $date);
                            $date = Carbon::createFromFormat('Y-m-d \a\t g:i a', $date);
                        } else {
                            $date = Carbon::createFromFormat('j M Y \a\t g:i a', $date);
                        }
                    } else {
                        $date = Carbon::now();
                    }

                    $title = null;
                    $rating = null;

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
