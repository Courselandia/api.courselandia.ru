<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Imports;

use Cache;
use Typography;
use Throwable;
use App\Models\Error;
use App\Models\Event;
use App\Modules\Promocode\Models\Promocode;
use App\Modules\Promocode\Entities\ParserPromocode;
use App\Modules\Promocode\Imports\Parsers\ParserNetology;
use App\Modules\Promocode\Imports\Parsers\ParserBangBangEducation;
use App\Modules\Promocode\Imports\Parsers\ParserCoddy;
use App\Modules\Promocode\Imports\Parsers\ParserContented;
use App\Modules\Promocode\Imports\Parsers\ParserEdusonAcademy;
use App\Modules\Promocode\Imports\Parsers\ParserGeekBrains;
use App\Modules\Promocode\Imports\Parsers\ParserHexlet;
use App\Modules\Promocode\Imports\Parsers\ParserInternationalSchoolProfessions;
use App\Modules\Promocode\Imports\Parsers\ParserInterra;
use App\Modules\Promocode\Imports\Parsers\ParserMaed;
use App\Modules\Promocode\Imports\Parsers\ParserOtus;
use App\Modules\Promocode\Imports\Parsers\ParserSkillbox;
use App\Modules\Promocode\Imports\Parsers\ParserSkillboxEng;
use App\Modules\Promocode\Imports\Parsers\ParserSkillFactory;
use App\Modules\Promocode\Imports\Parsers\ParserSkyPro;
use App\Modules\Promocode\Imports\Parsers\ParserXyzSchool;

/**
 * Класс импорта промокодов.
 */
class Import
{
    use Event;
    use Error;

    /**
     * ID обновленных промокодов.
     *
     * @var int[]|string[]
     */
    private array $ids = [];

    /**
     * Парсеры промокодов.
     *
     * @var Parser[]
     */
    private array $parsers = [];

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this
            ->addParser(new ParserNetology('https://api.advcake.com/promocodes?pass=WB0r4T6JRYz7_gwK&offer_id=29'))
            ->addParser(new ParserGeekBrains('https://api.advcake.com/promocodes?pass=WB0r4T6JRYz7_gwK&offer_id=403'))
            ->addParser(new ParserSkillbox('https://api.advcake.com/promocodes?pass=WB0r4T6JRYz7_gwK&offer_id=94'))
            ->addParser(new ParserSkyPro('https://api.advcake.com/promocodes?pass=WB0r4T6JRYz7_gwK&offer_id=566'))
            ->addParser(new ParserSkillFactory('https://api.advcake.com/promocodes?pass=WB0r4T6JRYz7_gwK&offer_id=389'))
            ->addParser(new ParserContented('https://api.advcake.com/promocodes?pass=WB0r4T6JRYz7_gwK&offer_id=442'))
            ->addParser(new ParserXyzSchool('https://api.advcake.com/promocodes?pass=WB0r4T6JRYz7_gwK&offer_id=417'))
            ->addParser(new ParserSkillboxEng('https://api.advcake.com/promocodes?pass=WB0r4T6JRYz7_gwK&offer_id=542'))
            ->addParser(new ParserInternationalSchoolProfessions('https://api.advcake.com/promocodes?pass=WB0r4T6JRYz7_gwK&offer_id=445'))
            ->addParser(new ParserEdusonAcademy('https://api.advcake.com/promocodes?pass=WB0r4T6JRYz7_gwK&offer_id=591'))
            ->addParser(new ParserCoddy('https://api.advcake.com/promocodes?pass=WB0r4T6JRYz7_gwK&offer_id=547'))
            ->addParser(new ParserOtus('https://api.advcake.com/promocodes?pass=WB0r4T6JRYz7_gwK&offer_id=491'))
            ->addParser(new ParserHexlet('https://api.advcake.com/promocodes?pass=WB0r4T6JRYz7_gwK&offer_id=527'))
            ->addParser(new ParserBangBangEducation('https://api.advcake.com/promocodes?pass=WB0r4T6JRYz7_gwK&offer_id=704'))
            ->addParser(new ParserInterra('https://api.advcake.com/promocodes?pass=WB0r4T6JRYz7_gwK&offer_id=489'))
            ->addParser(new ParserMaed('https://api.advcake.com/promocodes?pass=WB0r4T6JRYz7_gwK&offer_id=447'));
    }

    /**
     * Запуск импорта.
     *
     * @return void
     */
    public function run(): void
    {
        $this->offLimits();
        $this->import();
    }

    /**
     * Отключение лимитов.
     *
     * @return void
     */
    private function offLimits(): void
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        ignore_user_abort(true);
    }

    /**
     * Запуск импортирования.
     *
     * @return void
     */
    private function import(): void
    {
        $parsers = $this->getParsers();
        $this->clearIds();

        foreach ($parsers as $parser) {
            foreach ($parser->read() as $promocodeEntity) {
                $id = $this->save($promocodeEntity);

                if ($id) {
                    $this->addId($id);

                    $this->fireEvent(
                        'read',
                        [
                            $promocodeEntity
                        ]
                    );
                }
            }

            if ($parser->hasError()) {
                $errors = $parser->getErrors();

                foreach ($errors as $error) {
                    $this->addError($error);
                }
            }

            Promocode::where('school_id', $parser->getSchool()->value)
                ->whereNotIn('id', $this->getIds())
                ->whereNotNull('uuid')
                ->update([
                    'status' => false,
                ]);

            $this->clearIds();
        }

        Cache::tags(['promocode', 'school'])->flush();
    }

    /**
     * Сохранение промокода.
     *
     * @param ParserPromocode $promocodeEntity Промоакция.
     *
     * @return int|string|null Вернет ID промокода.
     */
    private function save(ParserPromocode $promocodeEntity): int|string|null
    {
        try {
            $promocode = Promocode::where('school_id', $promocodeEntity->school->value)
                ->where('uuid', $promocodeEntity->uuid)
                ->first();

            $title = html_entity_decode($promocodeEntity->title);
            $title = rtrim($title, '.');
            $description = html_entity_decode($promocodeEntity->description);

            if ($promocode) {
                $data = [
                    'code' => $promocodeEntity->code,
                    'title' => Typography::process($title, true),
                    'description' => Typography::process($description, true),
                    'min_price' => $promocodeEntity->min_price,
                    'discount' => $promocodeEntity->discount,
                    'discount_type' => $promocodeEntity->discount_type->value,
                    'date_start' => $promocodeEntity->date_start,
                    'date_end' => $promocodeEntity->date_end,
                    'type' => $promocodeEntity->type->value,
                    'url' => $promocodeEntity->url,
                    'status' => $promocodeEntity->status,
                ];

                $promocode->update($data);
            } else {
                $promocode = Promocode::create([
                    'uuid' => $promocodeEntity->uuid,
                    'code' => $promocodeEntity->code,
                    'school_id' => $promocodeEntity->school->value,
                    'title' => Typography::process($title, true),
                    'description' => Typography::process($description, true),
                    'min_price' => $promocodeEntity->min_price,
                    'discount' => $promocodeEntity->discount,
                    'discount_type' => $promocodeEntity->discount_type->value,
                    'date_start' => $promocodeEntity->date_start,
                    'date_end' => $promocodeEntity->date_end,
                    'type' => $promocodeEntity->type->value,
                    'url' => $promocodeEntity->url,
                    'status' => $promocodeEntity->status,
                ]);
            }

            return $promocode->id;
        } catch (Throwable $error) {
            $this->addError(
                $promocodeEntity->school?->getLabel()
                . ' | ' . $promocodeEntity->title
                . ' | ' . $error->getMessage()
            );
        }

        return null;
    }

    /**
     * Добавление парсера.
     *
     * @param Parser $parser парсер.
     * @return $this
     */
    public function addParser(Parser $parser): self
    {
        $this->parsers[] = $parser;

        return $this;
    }

    /**
     * Удаление всех парсеров.
     *
     * @return $this
     */
    public function clearParsers(): self
    {
        $this->parsers = [];

        return $this;
    }

    /**
     * Получение всех парсеров.
     *
     * @return Parser[]
     */
    public function getParsers(): array
    {
        return $this->parsers;
    }

    /**
     * Добавление ID обновленной промокода.
     *
     * @param int|string $id ID промокода.
     * @return $this
     */
    private function addId(int|string $id): self
    {
        $this->ids[] = $id;

        return $this;
    }

    /**
     * Удаление всех ID обновленных промокодов.
     *
     * @return $this
     */
    private function clearIds(): self
    {
        $this->ids = [];

        return $this;
    }

    /**
     * Получение всех ID обновленных промокодов.
     *
     * @return Parser[]
     */
    private function getIds(): array
    {
        return $this->ids;
    }
}
