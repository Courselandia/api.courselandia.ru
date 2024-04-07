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
 * Парсер для progbasics.ru
 */
class ParserProgbasics extends Parser
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
            $reviews = $driver->findElements(WebDriverBy::cssSelector('.comment'));

            foreach ($reviews as $review) {
                try {
                    sleep(2);

                    $title = null;
                    $name = $review->findElement(WebDriverBy::cssSelector('.comment__name'))->getText();
                    $date = $review->findElement(WebDriverBy::cssSelector('.comment__date'))->getText();
                    $texts = $review->findElements(WebDriverBy::cssSelector('.comment__info P, .comment__info UL'));
                    $rating = $review->findElement(WebDriverBy::cssSelector('.rating__star'));
                    $text = '';

                    foreach ($texts as $p) {
                        if (trim($p->getText()) === '') {
                            continue;
                        }

                        if ($text !== '') {
                            $text .= "\n";
                        }

                        $text .= $p->getText();
                    }

                    if (trim($rating->getAttribute('class')) === 'rating__star rating__star-5') {
                        $rating = 5;
                    } else if (trim($rating->getAttribute('class')) === 'rating__star rating__star-4') {
                        $rating = 4;
                    } else if (trim($rating->getAttribute('class')) === 'rating__star rating__star-3') {
                        $rating = 3;
                    } else if (trim($rating->getAttribute('class')) === 'rating__star rating__star-2') {
                        $rating = 2;
                    } else if (trim($rating->getAttribute('class')) === 'rating__star rating__star-2') {
                        $rating = 2;
                    } else if (trim($rating->getAttribute('class')) === 'rating__star rating__star-1') {
                        $rating = 1;
                    } else {
                        $rating = null;
                    }

                    $review = new ParserReview();
                    $review->title = $title;
                    $review->rating = $rating;
                    $review->date = $this->getDate($date);
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
     * Получение даты.
     *
     * @param string $date Строка даты.
     *
     * @return Carbon Вернет дату.
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

        $date = str_ireplace(array_keys($months), array_values($months), $date);

        return Carbon::createFromFormat('d.m.Y H:i:s', $date . ' 00:00:00');
    }
}
