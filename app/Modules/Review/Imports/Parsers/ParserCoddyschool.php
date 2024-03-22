<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Imports\Parsers;

use Storage;
use Generator;
use Throwable;
use Carbon\Carbon;
use App\Modules\Review\Imports\Parser;
use Facebook\WebDriver\WebDriverBy;
use App\Modules\Review\Imports\Browser;
use App\Modules\Review\Entities\ParserReview;

/**
 * Парсер для coddyschool.com
 */
class ParserCoddyschool extends Parser
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
            $script = 'window.scrollTo(0, document.body.scrollHeight);';

            $driver->get($this->getUrl());
            sleep(5);
            $driver->executeScript($script);
            sleep(5);

            $driver->findElement(WebDriverBy::cssSelector('.collect-button'))->click();

            do {
                $buttonMore = $driver->findElements(WebDriverBy::cssSelector('.center-btn .link.primary.loader'));
                $driver->takeScreenshot(Storage::drive('local')->path('/screens/all.jpg'));

                if (count($buttonMore)) {
                    $hasButtonMore = true;
                    $buttonMore[0]->takeElementScreenshot(Storage::drive('local')->path('/screens/button.jpg'));
                    $buttonMore[0]->click();
                    sleep(3);
                } else {
                    $hasButtonMore = false;
                }

            } while($hasButtonMore === true);

            $reviews = $driver->findElements(WebDriverBy::cssSelector('.item-review'));

            foreach ($reviews as $review) {
                try {
                    $name = $review->findElement(WebDriverBy::cssSelector('.comment-owner'))->getText();
                    $date = $review->findElement(WebDriverBy::cssSelector('.date'))->getText();
                    $title = null;
                    $reviewText = $review->findElement(WebDriverBy::cssSelector('.comment'))->getText();
                    $rating = 5;
                    $date = $this->getDate($date);

                    $review = new ParserReview();
                    $review->title = $title;
                    $review->rating = $rating;
                    $review->date = $date;
                    $review->name = $name;
                    $review->review = $reviewText;

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
     * @param string $date Дата в текстовом формате.
     *
     * @return Carbon Дата.
     */
    private function getDate(string $date): Carbon
    {
        $months = [
            'Январь ' => '01.',
            'Февраль ' => '02.',
            'Март ' => '03.',
            'Апрель ' => '04.',
            'Май ' => '05.',
            'Июнь ' => '06.',
            'Июль ' => '07.',
            'Август ' => '08.',
            'Сентябрь ' => '09.',
            'Октябрь ' => '10.',
            'Ноябрь ' => '11.',
            'Декабрь ' => '12.',
        ];

        $date = str_replace(array_keys($months), array_values($months), $date);

        return Carbon::createFromFormat('d.m.Y H:i:s', '01.'. $date . ' 00:00:00');
    }
}
