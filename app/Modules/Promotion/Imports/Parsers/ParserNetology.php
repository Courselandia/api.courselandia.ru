<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Imports\Parsers;

use Throwable;
use Carbon\Carbon;
use Generator;
use App\Modules\School\Enums\School;
use App\Modules\Promotion\Entities\ParserPromotion;
use App\Modules\Promotion\Imports\Parser;

/**
 * Парсинг промоакций Нетологии.
 */
class ParserNetology extends Parser
{
    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    public function getSchool(): School
    {
        return School::NETOLOGIA;
    }

    /**
     * Получение промоакции.
     *
     * @return Generator<ParserPromotion> Вернет одну промоакцию.
     */
    public function read(): Generator
    {
        try {
            $data = file_get_contents($this->getSource());
            $content = json_decode($data, true);

            foreach ($content['data'] as $item) {
                $promotion = new ParserPromotion();
                $promotion->uuid = $item['id'];
                $promotion->school = $this->getSchool();
                $promotion->title = $item['title'];
                $promotion->description = $item['description'];
                $promotion->date_start = $item['date_start'] ? Carbon::createFromFormat('Y-m-d',
                    $item['date_start']) : null;
                $promotion->date_end = $item['date_end'] ? Carbon::createFromFormat('Y-m-d', $item['date_end']) : null;
                $promotion->url = $item['landings'][0]['link'];
                $promotion->status = $item['active'];

                yield $promotion;
            }
        } catch (Throwable $error) {
            $this->addError(
                $this->getSchool()->getLabel()
                . ' | ' . $error->getMessage() . '.'
            );
        }
    }
}
