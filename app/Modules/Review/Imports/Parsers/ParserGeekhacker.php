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
 * Парсер для geekhacker.ru
 */
class ParserGeekhacker extends Parser
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
            sleep(3);
            $reviews = $driver->findElements(WebDriverBy::cssSelector('.commbox'));

            foreach ($reviews as $review) {
                try {
                    $name = $review->findElement(WebDriverBy::cssSelector('span.fn'))->getText();
                    $text = $browser->findElementIfExists($review, WebDriverBy::cssSelector('.comment-content'));

                    if (!$text) {
                        $text = $review->findElement(WebDriverBy::cssSelector('.comm_text_from_review'));
                    }

                    $text = $text->getText();
                    $date = $review->findElement(WebDriverBy::cssSelector('span.time'))->getText();
                    $date = Carbon::createFromFormat('d.m.Y \a\t H:i:s', $date . ':00');

                    $vendorReview = new ParserReview();
                    $vendorReview->date = $date;
                    $vendorReview->name = $name;
                    $vendorReview->review = $text;

                    yield $vendorReview;
                } catch (Throwable $error) {
                    $this->addError($this->getSchool()->getLabel() . ', из: ' . $this->getUrl() . ' : Не удается получить отзывов. ' . $error->getMessage());
                }
            }
        } catch (Throwable $error) {
            $this->addError($this->getSchool()->getLabel() . ', из: ' . $this->getUrl() . ' : Не удается получить список отзывов. ' . $error->getMessage());
        }
    }
}
