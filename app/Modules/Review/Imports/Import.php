<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Review\Imports;

use App\Modules\Review\Imports\Parsers\ParserContented;
use App\Modules\Review\Imports\Parsers\ParserIrecommend;
use App\Modules\Review\Imports\Parsers\ParserTutortop;
use App\Modules\School\Enums\School;
use Throwable;
use App\Models\Error;
use App\Models\Event;
use App\Modules\Review\Enums\Status;
use App\Modules\Review\Entities\ParserReview;
use App\Modules\Review\Models\Review;
use App\Modules\Review\Imports\Parsers\ParserKursvill;

/**
 * Импорт курсов с разных источников.
 */
class Import
{
    use Error;
    use Event;

    /**
     * Парсеры курсов.
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
            ->addParser(new ParserIrecommend(School::SKILLBOX, 'https://irecommend.ru/content/sait-skillbox-onlain-shkola'))
            /*->addParser(new TaskKatalogKursov(School::SKILLBOX, 'https://katalog-kursov.ru/reviews/school-skillbox/'))
            ->addParser(new TaskVk(School::XYZ_SCHOOL, 'https://vk.com/topic-124560669_34868074?offset=0'))
            ->addParser(new TaskMapsYandex(School::SKILLBOX, 'https://yandex.ru/maps/org/skillbox/4275407173/reviews/?ll=37.607031%2C55.727789&z=13'))
            ->addParser(new TaskMapsYandex(School::GEEKBRAINS, 'https://yandex.ru/maps/org/geekbrains/1402263817/reviews/'))
            ->addParser(new TaskMapsYandex(School::NETOLOGIA, 'https://yandex.ru/maps/org/netologiya/205031471256/reviews/'))
            ->addParser(new TaskMapsYandex(School::SKILL_FACTORY, 'https://yandex.ru/maps/org/skillfactory/237135461560/reviews/'))
            ->addParser(new TaskMapsYandex(School::CONTENTED, 'https://yandex.ru/maps/org/contented/115157665135/reviews/'))
            ->addParser(new TaskMapsYandex(School::XYZ_SCHOOL, 'https://yandex.ru/maps/org/xyz_school/151268379499/reviews/'))
            ->addParser(new TaskMapsYandex(School::INTERNATIONAL_SCHOOL_PROFESSIONS, 'https://yandex.ru/maps/org/mezhdunarodnaya_shkola_professiy/33978597831/reviews/'))
            ->addParser(new TaskMooc(School::SKILLBOX, 'skillbox'))
            ->addParser(new TaskMooc(School::NETOLOGIA, 'netology'))
            ->addParser(new TaskMooc(School::XYZ_SCHOOL, 'xyz-school'))
            ->addParser(new TaskMooc(School::GEEKBRAINS, 'geekbrains'))
            ->addParser(new TaskMooc(School::SKILL_FACTORY, 'skillfactory'))
            ->addParser(new TaskMooc(School::CONTENTED, 'contented-education-platform'))
            ->addParser(new TaskMooc(School::INTERNATIONAL_SCHOOL_PROFESSIONS, 'imba-akademia-cifrovogo-biznesa-ingate'))
            ->addParser(new TaskMooc(School::NETOLOGIA, 'https://netology.ru/otzyvy'))
            ->addParser(new TaskNetology(School::NETOLOGIA, 'https://netology.ru/otzyvy'))
            ->addParser(new TaskOtzyvru(School::SKILLBOX, 'https://www.otzyvru.com/skillbox'))
            ->addParser(new TaskProgbasics(School::SKILLBOX, 'https://progbasics.ru/schools/skillbox/reviews'))
            ->addParser(new TaskSpr(School::SKILLBOX, 'https://www.spr.ru/moskva/uchebnie-i-obrazovatelnie-tsentri-kursi/reviews/skillbox-5153272.html'))
            ->addParser(new TaskZoon(School::SKILLBOX, 'https://zoon.ru/msk/trainings/kompaniya_skillbox_na_leninskom_prospekte/reviews/'))
            ->addParser(new TaskSkillbox(School::SKILLBOX, 'https://skillbox.ru/otzyvy/'))
            ->addParser(new TaskOtzyvmarketing(School::SKILLBOX, 'https://otzyvmarketing.ru/skillbox/'))*/
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
                'name' => $entityReview->name,
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
}
