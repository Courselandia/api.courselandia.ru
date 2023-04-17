<?php

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
 * Парсер для contented.ru
 */
class ParserContented extends Parser
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
            $reviews = $driver->findElements(WebDriverBy::cssSelector('.r.t-rec.t-rec_pt_0.t-rec_pb_45.t-rec_pb-res-480_15'));

            foreach ($reviews as $review) {
                try {
                    $atoms = $review->findElements(WebDriverBy::cssSelector('.tn-atom'));
                    $name = ucfirst(mb_strtolower($atoms[4]->getText()));
                    $title = $atoms[3]->getText();
                    $reviewText = $atoms[2]->getText();
                    $rating = null;

                    $review = new ParserReview();
                    $review->title = $title;
                    $review->rating = $rating;
                    $review->date = Carbon::now();
                    $review->name = $name;
                    $review->review = $reviewText;

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
