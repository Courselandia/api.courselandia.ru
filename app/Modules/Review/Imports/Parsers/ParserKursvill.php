<?php

namespace App\Modules\Review\Imports\Parsers;

use DateTime;
use Generator;
use Throwable;
use Carbon\Carbon;
use App\Modules\Review\Imports\Parser;
use Facebook\WebDriver\WebDriverBy;
use App\Modules\Review\Imports\Browser;
use App\Modules\Review\Entities\ParserReview;

class ParserKursvill extends Parser
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
            $reviews = $driver->findElements(WebDriverBy::cssSelector('.reviews-block-list-item'));

            foreach ($reviews as $review) {
                $hasError = false;
                $name = null;
                $date = null;
                $title = null;
                $rating = null;
                $reviewText = null;
                $advantages = null;
                $disadvantages = null;

                try {
                    $name = $review->findElement(WebDriverBy::cssSelector('meta[itemprop="author"]'))->getAttribute('content');
                } catch (Throwable $error) {
                    $this->addError($this->getSchool()->getLabel() . ', from: ' . $this->getUrl() . ' : Не удается получить имя. ' . $error->getMessage());
                    $hasError = true;
                }

                try {
                    $date = $review->findElement(WebDriverBy::cssSelector('meta[itemprop="datePublished"]'))->getAttribute('content');
                    $date = Carbon::createFromFormat('d.m.Y H:i:s', $date . ' 00:00:00');
                } catch (Throwable $error) {
                    $this->addError($this->getSchool()->getLabel() . ', from: ' . $this->getUrl() . ' : Не удается получить дату. ' . $error->getMessage());
                    $hasError = true;
                }

                try {
                    $title = $review->findElement(WebDriverBy::cssSelector('meta[itemprop="itemReviewed"]'))->getAttribute('content');
                } catch (Throwable $error) {
                    $this->addError($this->getSchool()->getLabel() . ', from: ' . $this->getUrl() . ' : Не удается получить заголовок. ' . $error->getMessage());
                    $hasError = true;
                }

                try {
                    $rating = $review->findElement(WebDriverBy::cssSelector('meta[itemprop="ratingValue"]'))->getAttribute('content');
                } catch (Throwable $error) {
                    $this->addError($this->getSchool()->getLabel() . ', from: ' . $this->getUrl() . ' : Не удается получить рейтинг. ' . $error->getMessage());
                    $hasError = true;
                }

                try {
                    $reviewText = $review->findElement(WebDriverBy::cssSelector('span[itemprop="description"]'))->getText();
                } catch (Throwable $error) {
                    $this->addError($this->getSchool()->getLabel() . ', from: ' . $this->getUrl() . ' : Не удается получить описание. ' . $error->getMessage());
                    $hasError = true;
                }

                if (!$hasError) {
                    $proses = $review->findElements(WebDriverBy::cssSelector('div.pros p.plus'));

                    if ($proses) {
                        foreach ($proses as $plus) {
                            $advantages = $plus->getText() . PHP_EOL;
                        }
                    }

                    $cons = $review->findElements(WebDriverBy::cssSelector('div.cons p.minus'));

                    if ($cons) {
                        foreach ($cons as $plus) {
                            $disadvantages .= $plus->getText() . PHP_EOL;
                        }
                    }

                    $review = new ParserReview();
                    $review->name = $name;
                    $review->title = $title;
                    $review->review = $reviewText;
                    $review->advantages = $advantages;
                    $review->disadvantages = $disadvantages;
                    $review->rating = $rating;
                    $review->date = $date;

                    yield $review;
                }
            }
        } catch (Throwable $error) {
            $this->addError($this->getSchool()->getLabel() . ', from: ' . $this->getUrl() . ' : Не удается получить список отзывов. ' . $error->getMessage());
        }
    }
}
