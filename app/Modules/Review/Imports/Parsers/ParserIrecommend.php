<?php

namespace App\Modules\Review\Imports\Parsers;

use Generator;
use Throwable;
use Carbon\Carbon;
use App\Modules\Review\Imports\Parser;
use Facebook\WebDriver\WebDriverBy;
use App\Modules\Review\Imports\Browser;
use App\Modules\Review\Entities\ParserReview;

/**
 * Парсер для irecommend.ru
 */
class ParserIrecommend extends Parser
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
            $listList = [];

            $urlParts = parse_url($this->getUrl());
            $domain = $urlParts['scheme'] . '://' . $urlParts['host'];
            $driver->get($this->getUrl());

            $list = $driver->findElements(WebDriverBy::cssSelector('.rate .more'));

            $pager = $driver->findElements(WebDriverBy::cssSelector('.pager'));
            $hasPager = (bool)count($pager);
            $pageCount = 1;

            if ($hasPager) {
                $items = $pager[0]->findElements(WebDriverBy::cssSelector('.pager-item'));
                $pageCount = count($items) + 1;
            }

            foreach ($list as $review) {
                $reviewPage = $review->getAttribute('href');
                $listList[] = $domain . $reviewPage;
            }

            if ($hasPager) {
                for ($i = 1; $i <= $pageCount; $i++) {
                    $driver->switchTo()->window($driver->getWindowHandles()[0]);
                    $driver->get($this->getUrl() . '?page=' . $i);
                    $list = $driver->findElements(WebDriverBy::cssSelector('.rate .more'));

                    foreach ($list as $review) {
                        $reviewPage = $review->getAttribute('href');
                        $listList[] = $domain . $reviewPage;
                    }
                }
            }

            foreach ($listList as $link) {
                try {
                    $reviewDriver = $driver->get($link);

                    $dateValue = $reviewDriver
                        ->findElement(WebDriverBy::cssSelector('meta[itemprop="datePublished"]'))
                        ->getAttribute('content');

                    $authorValue = $reviewDriver->findElement(WebDriverBy::cssSelector('.reviewer a[itemprop="url"]'))->getText();

                    $ratingValue = $reviewDriver
                        ->findElement(WebDriverBy::cssSelector('.starsRating[itemprop="reviewRating"] meta[itemprop="ratingValue"]'))
                        ->getAttribute('content');

                    $titleValue = $reviewDriver
                        ->findElement(WebDriverBy::cssSelector('.reviewBlock .reviewTitle'))
                        ->getText();

                    $textValue = $reviewDriver
                        ->findElement(WebDriverBy::cssSelector('.reviewBlock .reviewText div[itemprop="reviewBody"]'))
                        ->getText();

                    $elem = $reviewDriver
                        ->findElements(WebDriverBy::cssSelector('.reviewBlock .ratio .plus'));

                    if ($elem) {
                        $textValue .= PHP_EOL . $elem[0]->getText();
                    }

                    $elem = $reviewDriver
                        ->findElements(WebDriverBy::cssSelector('.reviewBlock .ratio .minus'));

                    if ($elem) {
                        $textValue .= PHP_EOL . $elem[0]->getText();
                    }

                    $date = strtotime($dateValue);

                    $review = new ParserReview();
                    $review->title = $titleValue;
                    $review->rating = $ratingValue;
                    $review->date = Carbon::createFromFormat('U', $date);
                    $review->name = $authorValue;
                    $review->review = trim($textValue);

                    yield $review;
                } catch (Throwable $error) {
                    $this->addError($this->getSchool()->getLabel() . ', из: ' . $this->getUrl() . ' : Не удается получить список отзывов. ' . $error->getMessage());
                }
            }
        } catch (Throwable $error) {
            $this->addError($this->getSchool()->getLabel() . ', из: ' . $this->getUrl() . ' : Не удается получить список отзывов. ' . $error->getMessage());
        }
    }
}
