<?php
/**
 * Модуль Отзывов.
 * Этот модуль содержит все классы для работы с отзывами.
 *
 * @package App\Modules\Review
 */

namespace App\Modules\Review\Imports;

use Util;
use Throwable;
use App\Models\Error;
use App\Models\Event;
use App\Modules\School\Enums\School;
use App\Modules\Review\Enums\Status;
use App\Modules\Review\Models\Review;
use App\Modules\Review\Entities\ParserReview;
use App\Modules\Review\Imports\Parsers\ParserKursvill;
use App\Modules\Review\Imports\Parsers\ParserContented;
use App\Modules\Review\Imports\Parsers\ParserKatalogKursov;
use App\Modules\Review\Imports\Parsers\ParserMapsYandex;
use App\Modules\Review\Imports\Parsers\ParserMooc;
use App\Modules\Review\Imports\Parsers\ParserNetology;
use App\Modules\Review\Imports\Parsers\ParserOtzyvru;
use App\Modules\Review\Imports\Parsers\ParserProgbasics;
use App\Modules\Review\Imports\Parsers\ParserSpr;
use App\Modules\Review\Imports\Parsers\ParserTutortop;
use App\Modules\Review\Imports\Parsers\ParserVk;
use App\Modules\Review\Imports\Parsers\ParserOtzyvmarketing;
use App\Modules\Review\Imports\Parsers\ParserSkillbox;
use App\Modules\Review\Imports\Parsers\ParserZoon;

/**
 * Импорт отзывов с разных источников.
 */
class Import
{
    use Error;
    use Event;

    /**
     * Парсеры отзывов.
     *
     * @var Parser[]
     */
    private array $parsers = [];

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->addParser(new ParserKursvill(School::SKILLBOX, 'https://kursvill.ru/shkoly/skillbox.ru/?show=all#reviews'))
            ->addParser(new ParserKursvill(School::NETOLOGIA, 'https://kursvill.ru/shkoly/netology.ru/?show=all#reviews'))
            ->addParser(new ParserKursvill(School::XYZ_SCHOOL, 'https://kursvill.ru/shkoly/school-xyz.com/?show=all#reviews'))
            ->addParser(new ParserKursvill(School::GEEKBRAINS, 'https://kursvill.ru/shkoly/geekbrains.ru/?show=all#reviews'))
            ->addParser(new ParserKursvill(School::SKILL_FACTORY, 'https://kursvill.ru/shkoly/skillfactory.ru/?show=all#reviews'))
            ->addParser(new ParserTutortop(School::CONTENTED, 'https://tutortop.ru/school-reviews/contented/'))
            ->addParser(new ParserContented(School::CONTENTED, 'https://contented.ru/otzyvy'))
            ->addParser(new ParserKatalogKursov(School::SKILLBOX, 'https://katalog-kursov.ru/reviews/school-skillbox/'))
            ->addParser(new ParserVk(School::XYZ_SCHOOL, 'https://vk.com/topic-124560669_34868074?offset=0'))
            ->addParser(new ParserMapsYandex(School::SKILLBOX, 'https://yandex.ru/maps/org/skillbox/4275407173/reviews/?ll=37.607031%2C55.727789&z=13'))
            ->addParser(new ParserMapsYandex(School::GEEKBRAINS, 'https://yandex.ru/maps/org/geekbrains/1402263817/reviews/'))
            ->addParser(new ParserMapsYandex(School::NETOLOGIA, 'https://yandex.ru/maps/org/netologiya/205031471256/reviews/'))
            ->addParser(new ParserMapsYandex(School::SKILL_FACTORY, 'https://yandex.ru/maps/org/skillfactory/237135461560/reviews/'))
            ->addParser(new ParserMapsYandex(School::CONTENTED, 'https://yandex.ru/maps/org/contented/115157665135/reviews/'))
            ->addParser(new ParserMapsYandex(School::XYZ_SCHOOL, 'https://yandex.ru/maps/org/xyz_school/151268379499/reviews/'))
            ->addParser(new ParserMapsYandex(School::INTERNATIONAL_SCHOOL_PROFESSIONS, 'https://yandex.ru/maps/org/mezhdunarodnaya_shkola_professiy/33978597831/reviews/'))
            ->addParser(new ParserMooc(School::SKILLBOX, 'skillbox'))
            ->addParser(new ParserMooc(School::NETOLOGIA, 'netology'))
            ->addParser(new ParserMooc(School::XYZ_SCHOOL, 'xyz-school'))
            ->addParser(new ParserMooc(School::GEEKBRAINS, 'geekbrains'))
            ->addParser(new ParserMooc(School::SKILL_FACTORY, 'skillfactory'))
            ->addParser(new ParserMooc(School::CONTENTED, 'contented-education-platform'))
            ->addParser(new ParserMooc(School::INTERNATIONAL_SCHOOL_PROFESSIONS, 'imba-akademia-cifrovogo-biznesa-ingate'))
            ->addParser(new ParserMooc(School::NETOLOGIA, 'https://netology.ru/otzyvy'))
            ->addParser(new ParserNetology(School::NETOLOGIA, 'https://netology.ru/otzyvy'))
            ->addParser(new ParserOtzyvru(School::SKILLBOX, 'https://otzyvru.com/skillbox'))
            ->addParser(new ParserProgbasics(School::SKILLBOX, 'https://progbasics.ru/schools/skillbox/reviews'))
            ->addParser(new ParserSpr(School::SKILLBOX, 'https://spr.ru/moskva/uchebnie-i-obrazovatelnie-tsentri-kursi/reviews/skillbox-5153272.html'))
            ->addParser(new ParserZoon(School::SKILLBOX, 'https://zoon.ru/msk/trainings/kompaniya_skillbox_na_leninskom_prospekte/reviews/'))
            ->addParser(new ParserSkillbox(School::SKILLBOX, 'https://skillbox.ru/otzyvy/'))
            ->addParser(new ParserOtzyvmarketing(School::SKILLBOX, 'https://otzyvmarketing.ru/skillbox/'));
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
     * Отключить лимиты.
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
     * Запуск импорта.
     *
     * @return void
     */
    private function import(): void
    {
        foreach ($this->getParsers() as $parser) {
            $this->fireEvent(
                'school', [
                $parser->getSchool()
            ]);

            foreach ($parser->read() as $entityReview) {
                if ($this->isReviewEmpty($entityReview)) {
                    continue;
                }

                if (!$parser->isReviewExist($entityReview)) {
                    $id = $this->save($parser->getSchool(), $parser->getSource(), $parser->getUuid($entityReview), $entityReview);

                    if ($id) {
                        $entityReview->id = $id;

                        $this->fireEvent(
                            'imported', [
                            $entityReview,
                            $parser->getSchool(),
                            $parser->getSource(),
                        ]);
                    } else {
                        $errors = $parser->getErrors();

                        foreach ($errors as $error) {
                            $this->addError($error[0]);
                        }
                    }
                } else {
                    $review = $parser->getReview($entityReview);
                    $entityReview->id = $review->id;

                    $this->fireEvent(
                        'skipped', [
                        $entityReview,
                        $parser->getSchool(),
                        $parser->getSource(),
                    ]);
                }
            }

            if ($parser->hasError()) {
                foreach ($parser->getErrors() as $error) {
                    $this->addError($error);
                }
            }
        }
    }

    /**
     * Сохранить отзыв.
     *
     * @param School $school Школа.
     * @param string $source Источник.
     * @param string $uuid Уникальный ключ спарсенного отзыва.
     * @param ParserReview $entityReview Спарсенный отзыв.
     *
     * @return int|string|null Вернет ID созданного отзыва.
     */
    public function save(School $school, string $source, string $uuid, ParserReview $entityReview): int|string|null
    {
        try {
            $review = Review::create([
                'school_id' => $school->value,
                'uuid' => $uuid,
                'name' => Util::ucwords($entityReview->name),
                'title' => $entityReview->title,
                'review' => $entityReview->review,
                'advantages' => $entityReview->advantages,
                'disadvantages' => $entityReview->disadvantages,
                'rating' => $entityReview->rating,
                'created_at' => $entityReview->date,
                'source' => $source,
                'status' => Status::REVIEW->value,
            ]);

            return $review->id;
        } catch (Throwable $error) {
            $this->addError(
                $school->getLabel()
                . ' | ' . $entityReview->name
                . ' | ' . $error->getMessage()
            );
        }

        return null;
    }

    /**
     * Проверка, что отзыв не пустой.
     *
     * @param ParserReview $entityReview Спарсенный отзыв.
     *
     * @return bool Вернет true, если отзыв пустой.
     */
    public function isReviewEmpty(ParserReview $entityReview): bool
    {
        if (
            !$entityReview->name
            && !$entityReview->title
            && !$entityReview->review
            && !$entityReview->advantages
            && !$entityReview->disadvantages
            && !$entityReview->rating
        ) {
            return true;
        }

        return false;
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
     * @return Parser[] Массив парсеров.
     */
    public function getParsers(): array
    {
        return $this->parsers;
    }
}
