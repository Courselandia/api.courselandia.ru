<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Imports;

use Cache;
use Typography;
use Throwable;
use App\Models\Error;
use App\Models\Event;
use App\Modules\Promotion\Models\Promotion;
use App\Modules\Promotion\Entities\ParserPromotion;
use App\Modules\Promotion\Imports\Parsers\ParserNetology;
use App\Modules\Promotion\Imports\Parsers\ParserBangBangEducation;
use App\Modules\Promotion\Imports\Parsers\ParserCoddy;
use App\Modules\Promotion\Imports\Parsers\ParserContented;
use App\Modules\Promotion\Imports\Parsers\ParserEdusonAcademy;
use App\Modules\Promotion\Imports\Parsers\ParserGeekBrains;
use App\Modules\Promotion\Imports\Parsers\ParserHexlet;
use App\Modules\Promotion\Imports\Parsers\ParserInternationalSchoolProfessions;
use App\Modules\Promotion\Imports\Parsers\ParserInterra;
use App\Modules\Promotion\Imports\Parsers\ParserMaed;
use App\Modules\Promotion\Imports\Parsers\ParserOtus;
use App\Modules\Promotion\Imports\Parsers\ParserSkillbox;
use App\Modules\Promotion\Imports\Parsers\ParserSkillboxEng;
use App\Modules\Promotion\Imports\Parsers\ParserSkillFactory;
use App\Modules\Promotion\Imports\Parsers\ParserSkyPro;
use App\Modules\Promotion\Imports\Parsers\ParserXyzSchool;
use App\Modules\Promotion\Imports\Parsers\ParserAnoNiidpo;
use App\Modules\Promotion\Imports\Parsers\ParserNadpo;
use App\Modules\Promotion\Imports\Parsers\ParserProductStar;
use App\Modules\Promotion\Imports\Parsers\ParserPentaschool;
use App\Modules\Promotion\Imports\Parsers\ParserBrunoyam;
use App\Modules\Promotion\Imports\Parsers\ParserLogomashina;

/**
 * Класс импорта промоакций.
 */
class Import
{
    use Event;
    use Error;

    /**
     * ID обновленных промоакций.
     *
     * @var int[]|string[]
     */
    private array $ids = [];

    /**
     * Парсеры промоакций.
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
            ->addParser(new ParserNetology('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=29'))
            ->addParser(new ParserGeekBrains('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=403'))
            ->addParser(new ParserSkillbox('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=94'))
            ->addParser(new ParserSkyPro('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=566'))
            ->addParser(new ParserSkillFactory('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=389'))
            ->addParser(new ParserContented('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=442'))
            ->addParser(new ParserXyzSchool('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=417'))
            ->addParser(new ParserSkillboxEng('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=542'))
            ->addParser(new ParserInternationalSchoolProfessions('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=445'))
            ->addParser(new ParserEdusonAcademy('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=591'))
            ->addParser(new ParserCoddy('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=547'))
            ->addParser(new ParserOtus('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=491'))
            ->addParser(new ParserHexlet('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=527'))
            ->addParser(new ParserBangBangEducation('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=704'))
            ->addParser(new ParserInterra('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=489'))
            ->addParser(new ParserMaed('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=447'))
            ->addParser(new ParserAnoNiidpo('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=632'))
            ->addParser(new ParserNadpo('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=699'))
            ->addParser(new ParserProductStar('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=557'))
            ->addParser(new ParserPentaschool('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=750'))
            ->addParser(new ParserBrunoyam('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=497'))
            ->addParser(new ParserLogomashina('https://api.advcake.com/promotions?pass=WB0r4T6JRYz7_gwK&offer_id=669'))
        ;
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
            foreach ($parser->read() as $promotionEntity) {
                $id = $this->save($promotionEntity);

                if ($id) {
                    $this->addId($id);

                    $this->fireEvent(
                        'read',
                        [
                            $promotionEntity
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

            Promotion::where('school_id', $parser->getSchool()->value)
                ->whereNotIn('id', $this->getIds())
                ->whereNotNull('uuid')
                ->update([
                    'status' => false,
                ]);

            $this->clearIds();
        }

        Cache::tags(['promotion', 'school'])->flush();
    }

    /**
     * Сохранение промоакции.
     *
     * @param ParserPromotion $promotionEntity Промоакция.
     *
     * @return int|string|null Вернет ID промоакции.
     */
    private function save(ParserPromotion $promotionEntity): int|string|null
    {
        try {
            $promotion = Promotion::where('school_id', $promotionEntity->school->value)
                ->where('uuid', $promotionEntity->uuid)
                ->first();

            $title = html_entity_decode($promotionEntity->title);
            $title = rtrim($title, '.');
            $description = html_entity_decode($promotionEntity->description);

            if ($promotion) {
                $data = [
                    'title' => Typography::process($title, true),
                    'description' => Typography::process($description, true),
                    'date_start' => $promotionEntity->date_start,
                    'date_end' => $promotionEntity->date_end,
                    'url' => $promotionEntity->url,
                    'status' => $promotionEntity->status,
                ];

                $promotion->update($data);
            } else {
                $promotion = Promotion::create([
                    'uuid' => $promotionEntity->uuid,
                    'school_id' => $promotionEntity->school->value,
                    'title' => Typography::process($title, true),
                    'description' => Typography::process($description, true),
                    'date_start' => $promotionEntity->date_start,
                    'date_end' => $promotionEntity->date_end,
                    'url' => $promotionEntity->url,
                    'status' => $promotionEntity->status,
                ]);
            }

            return $promotion->id;
        } catch (Throwable $error) {
            $this->addError(
                $promotionEntity->school?->getLabel()
                . ' | ' . $promotionEntity->title
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
     * Добавление ID обновленной промоакции.
     *
     * @param int|string $id ID промоакции.
     * @return $this
     */
    private function addId(int|string $id): self
    {
        $this->ids[] = $id;

        return $this;
    }

    /**
     * Удаление всех ID обновленных промоакций.
     *
     * @return $this
     */
    private function clearIds(): self
    {
        $this->ids = [];

        return $this;
    }

    /**
     * Получение всех ID обновленных промоакций.
     *
     * @return Parser[]
     */
    private function getIds(): array
    {
        return $this->ids;
    }
}
