<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Imports\Parsers;

use Throwable;
use Carbon\Carbon;
use Generator;
use App\Modules\School\Enums\School;
use App\Modules\Promocode\Entities\ParserPromocode;
use App\Modules\Promocode\Imports\Parser;
use App\Modules\Promocode\Enums\DiscountType;
use App\Modules\Promocode\Enums\Type;

/**
 * Парсинг промокодов Нетологии.
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
     * Получение промокода.
     *
     * @return Generator<ParserPromocode> Вернет один промокод.
     */
    public function read(): Generator
    {
        try {
            $data = file_get_contents($this->getSource());
            $content = json_decode($data, true);

            foreach ($content['data'] as $item) {
                $promocode = new ParserPromocode();
                $promocode->uuid = $item['id'];
                $promocode->school = $this->getSchool();
                $promocode->code = $item['name'];
                $promocode->title = $item['short_name'];
                $promocode->description = $item['description'];
                $promocode->min_price = $item['min_price'];
                $promocode->discount = $item['discount'];
                $promocode->discount_type = DiscountType::from($item['discount']);
                $promocode->date_start = $item['date_start'] ? Carbon::createFromFormat('Y-m-d',
                    $item['date_start']) : null;
                $promocode->date_end = $item['date_end'] ? Carbon::createFromFormat('Y-m-d', $item['date_end']) : null;
                $promocode->type = Type::from($item['type']);
                $promocode->url = $item['referral_link'];
                $promocode->status = $item['active'];

                yield $promocode;
            }
        } catch (Throwable $error) {
            $this->addError(
                $this->getSchool()->getLabel()
                . ' | ' . $error->getMessage() . '.'
            );
        }
    }
}
