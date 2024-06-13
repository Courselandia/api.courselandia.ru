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
use App\Models\Exceptions\RecordExistException;
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
use App\Modules\Review\Imports\Parsers\ParserCoddyschool;
use App\Modules\Review\Imports\Parsers\ParserGeekhacker;
use App\Modules\Review\Imports\Parsers\ParserHexlet;
use App\Modules\Review\Imports\Parsers\ParserKursyOnline;

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
            ->addParser(new ParserTutortop(School::SKYPRO, 'https://tutortop.ru/school-reviews/skypro/'))
            ->addParser(new ParserContented(School::CONTENTED, 'https://contented.ru/otzyvy'))
            ->addParser(new ParserKatalogKursov(School::SKILLBOX, 'https://katalog-kursov.ru/reviews/school-skillbox/'))
            ->addParser(new ParserVk(School::XYZ_SCHOOL, 'https://vk.com/topic-124560669_34868074?offset=0'))
            ->addParser(new ParserMapsYandex(School::SKILLBOX, 'https://yandex.ru/maps/org/skillbox/4275407173/reviews/?ll=37.607031%2C55.727789&z=13'))
            ->addParser(new ParserMapsYandex(School::SKYPRO, 'https://yandex.ru/maps/org/skypro/121650580880/reviews/'))
            ->addParser(new ParserMapsYandex(School::GEEKBRAINS, 'https://yandex.ru/maps/org/geekbrains/1402263817/reviews/'))
            ->addParser(new ParserMapsYandex(School::NETOLOGIA, 'https://yandex.ru/maps/org/netologiya/205031471256/reviews/'))
            ->addParser(new ParserMapsYandex(School::XYZ_SCHOOL, 'https://yandex.ru/maps/org/xyz_school/151268379499/reviews/'))
            ->addParser(new ParserMooc(School::SKILLBOX, 'skillbox'))
            ->addParser(new ParserMooc(School::NETOLOGIA, 'netology'))
            ->addParser(new ParserMooc(School::XYZ_SCHOOL, 'xyz-school'))
            ->addParser(new ParserMooc(School::GEEKBRAINS, 'geekbrains'))
            ->addParser(new ParserMooc(School::SKILL_FACTORY, 'skillfactory'))
            ->addParser(new ParserMooc(School::CONTENTED, 'contented-education-platform'))
            ->addParser(new ParserMooc(School::INTERNATIONAL_SCHOOL_PROFESSIONS, 'imba-akademia-cifrovogo-biznesa-ingate'))
            ->addParser(new ParserMooc(School::NETOLOGIA, 'https://netology.ru/otzyvy'))
            ->addParser(new ParserNetology(School::NETOLOGIA, 'https://netology.ru/otzyvy'))
            ->addParser(new ParserOtzyvmarketing(School::SKILLBOX, 'https://otzyvmarketing.ru/skillbox/'))
            ->addParser(new ParserOtzyvmarketing(School::SKYPRO, 'https://otzyvmarketing.ru/skypro/'))
            ->addParser(new ParserMapsYandex(School::EDUSON_ACADEMY, 'https://yandex.ru/maps/org/eduson/64475613644/reviews/?ll=37.652226%2C55.708203&z=13'))
            ->addParser(new ParserMapsYandex(School::CODDY, 'https://yandex.ru/maps/org/coddy/1796824613/reviews/'))
            ->addParser(new ParserMooc(School::CODDY, 'coddyschool'))
            ->addParser(new ParserKursvill(School::OTUS, 'https://kursvill.ru/shkoly/otus.ru/?show=all#reviews'))
            ->addParser(new ParserMapsYandex(School::OTUS, 'https://yandex.ru/maps/org/otus_onlayn_obrazovaniye/198118878045/reviews/'))
            ->addParser(new ParserMooc(School::OTUS, 'otus'))
            ->addParser(new ParserKursvill(School::HEXLET, 'https://kursvill.ru/shkoly/hexlet.io/?show=all#reviews'))
            ->addParser(new ParserHexlet(School::HEXLET, 'https://ru.hexlet.io/testimonials'))
            ->addParser(new ParserMapsYandex(School::HEXLET, 'https://yandex.ru/maps/org/khekslet/149793747773/reviews/'))
            ->addParser(new ParserMooc(School::HEXLET, 'hexlet'))
            ->addParser(new ParserGeekhacker(School::BANG_BANG_EDUCATION, 'https://geekhacker.ru/otzyvy-bang-bang-education/'))
            ->addParser(new ParserTutortop(School::BANG_BANG_EDUCATION, 'https://tutortop.ru/school-reviews/bang-bang-education/'))
            ->addParser(new ParserKatalogKursov(School::BANG_BANG_EDUCATION, 'https://katalog-kursov.ru/reviews/bang-bang-education/'))
            ->addParser(new ParserKursyOnline(School::BANG_BANG_EDUCATION, 'https://kursy-online.ru/reviews/bangbangeducation-ru/'))
            ->addParser(new ParserMapsYandex(School::BANG_BANG_EDUCATION, 'https://yandex.ru/maps/org/bang_bang_education/232375753768/reviews/'))
            ->addParser(new ParserKursvill(School::INTERRA, 'https://kursvill.ru/shkoly/interra/?show=all#reviews'))
            ->addParser(new ParserKursvill(School::MAED, 'https://kursvill.ru/shkoly/maed/?show=all#reviews'))
            ->addParser(new ParserMapsYandex(School::MAED, 'https://yandex.ru/maps/org/marketingovoye_obrazovaniye/119627619376/reviews/'))
            ->addParser(new ParserMooc(School::MAED, 'maed'))
            ->addParser(new ParserMapsYandex(School::SKILL_FACTORY, 'https://yandex.ru/maps/org/skillfactory/237135461560/reviews/'))
            ->addParser(new ParserMapsYandex(School::CONTENTED, 'https://yandex.ru/maps/org/contented/56878205703/reviews/'))
            ->addParser(new ParserMapsYandex(School::INTERNATIONAL_SCHOOL_PROFESSIONS, 'https://yandex.ru/maps/org/mezhdunarodnaya_shkola_professiy/80806979609/reviews/'))
            ->addParser(new ParserOtzyvru(School::SKILLBOX, 'https://otzyvru.com/skillbox'))
            ->addParser(new ParserProgbasics(School::SKILLBOX, 'https://progbasics.ru/schools/skillbox/reviews'))
            ->addParser(new ParserProgbasics(School::SKYPRO, 'https://progbasics.ru/schools/skypro/reviews'))
            ->addParser(new ParserSpr(School::SKILLBOX, 'https://spr.ru/moskva/uchebnie-i-obrazovatelnie-tsentri-kursi/reviews/skillbox-5153272.html'))
            ->addParser(new ParserZoon(School::SKILLBOX, 'https://zoon.ru/msk/trainings/kompaniya_skillbox_na_leninskom_prospekte/reviews/'))
            ->addParser(new ParserSkillbox(School::SKILLBOX, 'https://skillbox.ru/otzyvy/'))
            ->addParser(new ParserCoddyschool(School::CODDY, 'https://berlin.coddyschool.com/vse-otzyvy/'))
            ->addParser(new ParserTutortop(School::ANO_NIIDPO, 'https://tutortop.ru/school-reviews/niidpo/'))
            ->addParser(new ParserKatalogKursov(School::ANO_NIIDPO, 'https://katalog-kursov.ru/reviews/niidpo/'))
            ->addParser(new ParserMapsYandex(School::ANO_NIIDPO, 'https://yandex.ru/maps/org/natsionalny_issledovatelskiy_institut_dopolnitelnogo_obrazovaniya_i_professionalnogo_obucheniya/68115635100/reviews/'))
            ->addParser(new ParserOtzyvru(School::ANO_NIIDPO, 'https://www.otzyvru.com/natsionalniy-issledovatelskiy-institut-dopolnitelnogo-obrazovaniya-i'))
            ->addParser(new ParserProgbasics(School::ANO_NIIDPO, 'https://progbasics.ru/schools/niidpo'))
            ->addParser(new ParserZoon(School::ANO_NIIDPO, 'https://zoon.ru/msk/trainings/natsionalnyj_issledovatelskij_institut_dopolnitelnogo_obrazovaniya_i_professionalnogo_obucheniya/reviews/'))
            ->addParser(new ParserTutortop(School::NADPO, 'https://tutortop.ru/school-reviews/nadpo/'))
            ->addParser(new ParserKatalogKursov(School::NADPO, 'https://katalog-kursov.ru/reviews/nadpo/'))
            ->addParser(new ParserMapsYandex(School::NADPO, 'https://yandex.ru/maps/org/natsionalnaya_akademiya_dopolnitelnogo_professionalnogo_obrazovaniya/98271748609/reviews/?ll=37.592950%2C55.660998&tab=reviews&z=17.03'))
            ->addParser(new ParserOtzyvru(School::NADPO, 'https://www.otzyvru.com/nadpo'))
            ->addParser(new ParserProgbasics(School::NADPO, 'https://progbasics.ru/schools/nadpo/reviews'))
            ->addParser(new ParserZoon(School::NADPO, 'https://zoon.ru/msk/trainings/natsionalnaya_akademiya_dopolnitelnogo_professionalnogo_obrazovaniya/reviews/'))
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
    private function save(School $school, string $source, string $uuid, ParserReview $entityReview): int|string|null
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
        } catch (RecordExistException) {
            return null;
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
     * Проверка, что отзыв пустой.
     *
     * @param ParserReview $entityReview Спарсенный отзыв.
     *
     * @return bool Вернет true, если отзыв пустой.
     */
    private function isReviewEmpty(ParserReview $entityReview): bool
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
