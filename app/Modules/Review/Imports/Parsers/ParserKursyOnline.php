<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
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
 * Парсер для kursy-online.ru
 */
class ParserKursyOnline extends Parser
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
            $reviews = $driver->findElements(WebDriverBy::cssSelector('.js_comment__item_x'));

            foreach ($reviews as $review) {
                try {
                    $name = $review->findElement(WebDriverBy::cssSelector('.review__q__item__name'))->getText();
                    $date = $review->findElement(WebDriverBy::cssSelector('.review__q__item__date'))->getText();
                    $title = $review->findElement(WebDriverBy::cssSelector('.review__q__item__title'))->getText();
                    $text = $review->findElement(WebDriverBy::cssSelector('.reviewText__short_inner'))->getText();
                    $rating = $review->findElement(WebDriverBy::cssSelector('.review__q__item__raiting'))->getText();

                    $rating = (int)trim(explode('/', $rating)[0]);
                    $date = $this->getDate($date);

                    $review = new ParserReview();
                    $review->title = $title;
                    $review->rating = min($rating, 5);
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

    private function getDate(string $date): Carbon
    {
        $date = trim($date);

        $months = [
            ' Января ' => '.01.',
            ' Февраля ' => '.02.',
            ' Марта ' => '.03.',
            ' Апреля ' => '.04.',
            ' Мая ' => '.05.',
            ' Июня ' => '.06.',
            ' Июля ' => '.07.',
            ' Августа ' => '.08.',
            ' Сентября ' => '.09.',
            ' Октября ' => '.10.',
            ' Ноября ' => '.11.',
            ' Декабря ' => '.12.',
        ];

        $date = str_ireplace(array_keys($months), array_values($months), $date);

        return Carbon::createFromFormat('d.m.Y H:i:s', $date . ' 00:00:00');
    }
}
