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
 * Парсер для hexlet.io
 */
class ParserHexlet extends Parser
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
            $driver->get($this->getUrl() . '?page=1');
            sleep(5);

            $pageButtonToLast = $driver->findElement(WebDriverBy::cssSelector('.page-item:last-child A'));
            $pageButtonToLast->click();
            sleep(5);
            $totalPages = (int)$driver->findElement(WebDriverBy::cssSelector('.page-item.active A'))->getText();

            for ($page = 1; $page <= $totalPages; $page++) {
                $driver->get($this->getUrl() . '?page=' . $page);
                sleep(3);

                $reviews = $driver->findElements(WebDriverBy::cssSelector('.hexlet-dashboard-courses .mb-3.pb-5'));

                foreach ($reviews as $review) {
                    try {
                        $name = $review->findElement(WebDriverBy::cssSelector('H5.mt-3'))->getText();
                        $title = null;
                        $text = trim($review->findElement(WebDriverBy::cssSelector('.fs-5.fw-light.lh-lg'))->getText());
                        $rating = null;
                        $date = $review->findElement(WebDriverBy::cssSelector('TIME'))->getAttribute('datetime');
                        $date = Carbon::createFromFormat('Y-m-d\TH:i:s', $date . ':00');

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
            }
        } catch (Throwable $error) {
            $this->addError($this->getSchool()->getLabel() . ', из: ' . $this->getUrl() . ' : Не удается получить список отзывов. ' . $error->getMessage());
        }
    }
}
