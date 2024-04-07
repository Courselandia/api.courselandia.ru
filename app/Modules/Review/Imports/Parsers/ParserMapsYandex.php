<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Imports\Parsers;

use Exception;
use Generator;
use Throwable;
use Carbon\Carbon;
use App\Modules\Review\Imports\Parser;
use Facebook\WebDriver\WebDriverBy;
use App\Modules\Review\Imports\Browser;
use App\Modules\Review\Entities\ParserReview;

/**
 * Парсер для maps.yandex.ru
 */
class ParserMapsYandex extends Parser
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
            sleep(15);

            $perPage = 50;
            $totalReviews = (int)$driver->findElement(WebDriverBy::cssSelector('.card-section-header__title._wide'))->getText();
            $scrolls = ceil($totalReviews / $perPage);

            $script = '
                const scrolls = ' . $scrolls . ';

                for (var i = 0; i < scrolls; i++) {
                    window.setTimeout(function () {
                        const elements = document.getElementsByClassName("scroll__container");
                        const container = elements[0];
                        container.scrollTo(0, container.scrollHeight - 2000);

                        window.setTimeout(function() {
                            const elementLast = document.querySelector(".business-reviews-card-view__review:last-child");
                            elementLast.scrollIntoView({block: "start", behavior: "smooth"});
                            console.log("Scrolling...");
                            }, 1500
                        )

                    }, 4000 * i);
                }
            ';

            $seconds = (ceil($totalReviews / $perPage) * 6) + 5;
            $driver->executeScript($script);
            sleep($seconds);

            $reviews = $driver->findElements(WebDriverBy::cssSelector('.business-reviews-card-view__review'));

            foreach ($reviews as $review) {
                try {
                    $title = null;
                    $name = $review
                        ->findElement(WebDriverBy::cssSelector('.business-review-view__link SPAN, .business-review-view__author-info DIV SPAN'))
                        ->getText();
                    $text = $review->findElement(WebDriverBy::cssSelector('.business-review-view__body-text'))->getText();
                    $rating = count($review->findElements(WebDriverBy::cssSelector('.business-rating-badge-view__star._full')));

                    $date = $review->findElements(WebDriverBy::cssSelector('META[itemprop="datePublished"]'));

                    if (!count($date)) {
                        $date = $review->findElements(WebDriverBy::cssSelector('.business-review-view__date SPAN'));
                        $date = $this->getDate($date[0]->getText());
                    } else {
                        $date = $date[0]->getAttribute('content');
                        $date = Carbon::parse($date);
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

    /**
     * Получение даты публикации.
     *
     * @param string $date Дата в виде строки.
     *
     * @return Carbon|null Дата.
     */
    private function getDate(string $date): ?Carbon
    {
        $months = [
            ' января' => '.01.',
            ' февраля' => '.02.',
            ' марта' => '.03.',
            ' апреля' => '.04.',
            ' мая' => '.05.',
            ' июня' => '.06.',
            ' июля' => '.07.',
            ' августа' => '.08.',
            ' сентября' => '.09.',
            ' октября' => '.10.',
            ' ноября' => '.11.',
            ' декабря' => '.12.',
        ];

        $date = str_replace(array_keys($months), array_values($months), $date);
        $date = str_replace(' ', '', $date);

        try {
            return Carbon::createFromFormat('d.m.Y H:i:s', $date . ' 00:00:00');
        } catch(Exception $error) {
            return Carbon::createFromFormat('d.m.Y H:i:s', $date . date('Y') . ' 00:00:00');
        }
    }
}
