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
 * Парсер для zoon.ru
 */
class ParserZoon extends Parser
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

            $total = $driver->findElements(WebDriverBy::cssSelector('.js-reviews-top-panel .service-block-title'));
            $total = (int)str_replace('Все отзывы подряд ', '', $total[0]->getText());
            $perPage = 50;
            $buttons = ceil($total / $perPage) - 1;

            $scriptLoadAllReviews = '
                function openAllReviews()
                {
                    const buttons = document.querySelectorAll(".js-show-more-box.pd-lxl.pt0 .js-show-more");
                    let actions = 0;

                    for (i = 0; i < buttons.length; i++) {
                        if (buttons[i].offsetParent) {
                            buttons[i].click();
                            actions++;
                        }
                    }

                    if (actions) {
                        window.setTimeout(openAllReviews, 3000);
                    }
                }

                openAllReviews();
            ';

            $driver->executeScript($scriptLoadAllReviews);
            sleep(10 * $buttons);

            $scriptOpenAllReviews = '
                const hiddenText = document.querySelectorAll(".js-comment-additional-text.hidden");

                for(let i = 0; i < hiddenText.length; i++) {
                    hiddenText[i].classList.remove("hidden");
                }
            ';

            $driver->executeScript($scriptOpenAllReviews);
            sleep(5);

            $contents = $driver->findElements(WebDriverBy::cssSelector('.list-reset.feedbacks-new.js-feedbacks-new'));
            $reviews = $contents[0]->findElements(WebDriverBy::cssSelector('.list-reset.feedbacks-new.js-feedbacks-new .js-comment'));

            foreach ($reviews as $review) {
                try {
                    $title = null;

                    try {
                        $name = $review->findElement(WebDriverBy::cssSelector('SPAN[itemprop="name"]'))->getText();
                    } catch (Throwable $error) {
                        break;
                    }

                    $text = $review->findElement(
                        WebDriverBy::cssSelector('.js-comment-short-text.comment-text .js-comment-content')
                    )->getText();

                    $textAdditional = $review->findElements(
                        WebDriverBy::cssSelector('.js-comment-short-text.comment-text .js-comment-additional-text')
                    );

                    if (count($textAdditional)) {
                        $text .= $textAdditional[0]->getText();
                    }

                    try {
                        $rating = $review->findElement(WebDriverBy::cssSelector('.stars-rating-text'))->getText();
                        $rating = intval($rating);
                    } catch (Throwable $error) {
                        $rating = null;
                    }

                    $date = $review->findElement(WebDriverBy::cssSelector('.z-text--dark-gray'))->getText();
                    $date = explode(' , ', $date);
                    $date = $date[0];

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
     * Получить дату отзыва.
     *
     * @param string $date Дата в виде строки.
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

        return Carbon::createFromFormat('j.m.Y в H:i:s', $date . ':00');
    }
}
