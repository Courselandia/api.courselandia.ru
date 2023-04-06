<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Imports;

use App\Modules\Course\Enums\Currency;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Metatag\Template\Template;
use Cache;
use Throwable;
use Util;
use File;
use ImageStore;
use App\Models\Error;
use App\Modules\Course\Entities\ParserCourse;
use App\Models\Event;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Enums\Status;
use Illuminate\Http\UploadedFile;
use App\Modules\Course\Imports\Parsers\ParserNetology;
use App\Modules\Course\Imports\Parsers\ParserGeekBrains;
use App\Modules\Course\Imports\Parsers\ParserSkillbox;
use App\Modules\Course\Imports\Parsers\ParserSkyPro;
use App\Modules\Course\Imports\Parsers\ParserContented;
use App\Modules\Course\Imports\Parsers\ParserSkillFactory;
use App\Modules\Course\Imports\Parsers\ParserXyzSchool;
use App\Modules\Course\Imports\Parsers\ParserSkillboxEng;
use App\Modules\Course\Imports\Parsers\ParserInternationalSchoolProfessions;

/**
 * Класс импорта курсов.
 */
class Import
{
    use Event;
    use Error;

    /**
     * ID обновленных курсов.
     *
     * @var int[]|string[]
     */
    private array $ids = [];

    /**
     * Парсеры курсов.
     *
     * @var Parser[]
     */
    private array $parsers = [];

    /**
     * Свойство определяющее нужно ли перезагружать все изображения.
     *
     * @var bool
     */
    private bool $reloadImages = false;

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->addParser(new ParserNetology('https://feeds.advcake.com/feed/download/54321b01372443d9902b3387dd154c8f'))
            ->addParser(new ParserGeekBrains('https://feeds.advcake.com/feed/download/07d89f2e6d9a92a355caa2d4db424cfd'))
            ->addParser(new ParserSkillbox('https://feeds.advcake.com/feed/download/04c98be3ff7eb4298b14b863b66f5447')) // Боевой
            ->addParser(new ParserSkyPro('https://feeds.advcake.com/feed/download/3ae0ebb5de2e80b35668ba8b3db8b760'))
            ->addParser(new ParserSkillFactory('https://feeds.advcake.com/feed/download/993eb3d8a8131641e035707f860fef7d'))
            ->addParser(new ParserContented('https://feeds.advcake.com/feed/download/5a6f3f438c1f38f77256e65abc44e780'))
            ->addParser(new ParserXyzSchool('https://feeds.advcake.com/feed/download/ef05e6d05099e4672126b4cddeeec695'))
            ->addParser(new ParserSkillboxEng('https://feeds.advcake.com/feed/download/8aeda3dc397f0e13147ae10a8075de92'))
            ->addParser(new ParserInternationalSchoolProfessions('https://feeds.advcake.com/feed/download/335b838a5b67c60fd7d67e7f0e0e4ae5'));
    }

    /**
     * Запуск импорта.
     *
     * @return void
     */
    public function run(): void
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        ignore_user_abort(true);

        $parsers = $this->getParsers();

        foreach ($parsers as $parser) {
            foreach ($parser->read() as $courseEntity) {
                $id = $this->save($courseEntity);

                if ($id) {
                    $this->addId($id);

                    $this->fireEvent(
                        'read',
                        [
                            $courseEntity
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

            Course::where('school_id', $parser->getSchool()->value)
                ->whereNotIn('id', $this->getIds())
                ->update([
                    'status' => Status::DISABLED->value
                ]);

            $this->clearIds();
        }

        Cache::tags([
            'course',
            'direction',
            'profession',
            'category',
            'skill',
            'teacher',
            'tool',
            'processes',
            'employment',
            'review',
        ])->flush();
    }

    /**
     * Установка свойства нужно ли перезагружать все изображения.
     *
     * @param bool $status Статус.
     *
     * @return $this
     */
    public function setReloadImages(bool $status): self
    {
        $this->reloadImages = $status;

        return $this;
    }

    /**
     * Получения свойства нужно ли перезагружать все изображения.
     *
     * @return bool Вернет статус.
     */
    public function getReloadImages(): bool
    {
        return $this->reloadImages;
    }

    /**
     * Сохранение курса.
     *
     * @param ParserCourse $courseEntity Курс.
     *
     * @return int|string|null Вернет ID курса.
     */
    private function save(ParserCourse $courseEntity): int|string|null
    {
        try {
            $course = Course::where('school_id', $courseEntity->school->value)
                ->where('uuid', $courseEntity->uuid)
                ->first();

            if ($course) {
                if ($courseEntity->status) {
                    if ($course->status === Status::DISABLED->value) {
                        $status = Status::DRAFT->value;
                    } else {
                        $status = $course->status;
                    }
                } else {
                    $status = Status::DISABLED->value;
                }

                $data = [
                    'name' => $courseEntity->name,
                    'link' => strtolower(Util::latin(strtolower($courseEntity->name))),
                    'status' => $status,
                    'url' => $courseEntity->url,
                    'price' => $courseEntity->price,
                    'price_old' => $courseEntity->price_old,
                    'price_recurrent' => $courseEntity->price_recurrent,
                    'currency' => $courseEntity->currency?->value,
                    'school_id' => $courseEntity->school?->value,
                    'duration' => $courseEntity->duration ?: $course->duration,
                    'duration_unit' => $courseEntity->duration_unit?->value ?: $course->duration_unit,
                    'lessons_amount' => $courseEntity->lessons_amount ?: $course->lessons_amount,
                    'employment' => $courseEntity->employment ?: $course->employment,
                ];

                if ($this->getReloadImages()) {
                    $image = $courseEntity->image ? $this->getImage($courseEntity->image) : null;

                    $data['image_small_id'] = $image;
                    $data['image_middle_id'] = $image;
                    $data['image_big_id'] = $image;
                }

                $course->update($data);
            } else {
                $template = new Template();
                $action = app(MetatagSetAction::class);

                $templateValues = [
                    'course' => $courseEntity->name,
                    'school' => $courseEntity->school->getLabel(),
                    'price' => $courseEntity->price,
                    'currency' => $courseEntity->currency?->value || Currency::RUB,
                ];

                $templateTitle = 'Курс {course} от {school:genitive} [price:по цене {price}|бесплатно] — Courselandia';
                $templateDescription = 'Приступите к программе обучения прям сейчас онлайн-курса {course} от {school:genitive} выбрав его в каталоге Courselandia, легкий поиск, возможность сравнивать курсы по разным параметрам';
                $headerTemplate = '{course} от {school:genitive}';

                $action->title = $template->convert($templateTitle, $templateValues);
                $action->description = $template->convert($templateDescription, $templateValues);
                $action->title_template = $templateTitle;
                $action->description_template = $templateDescription;

                $metatag = $action->run();

                $image = $courseEntity->image ? $this->getImage($courseEntity->image) : null;

                $course = Course::create([
                    'uuid' => $courseEntity->uuid,
                    'name' => $courseEntity->name,
                    'header' => $template->convert($headerTemplate, $templateValues),
                    'header_template' => $headerTemplate,
                    'link' => strtolower(Util::latin(strtolower($courseEntity->name))),
                    'text' => $courseEntity->text,
                    'status' => $courseEntity->status ? Status::DRAFT->value : Status::DISABLED->value,
                    'url' => $courseEntity->url,
                    'image_small_id' => $image,
                    'image_middle_id' => $image,
                    'image_big_id' => $image,
                    'price' => $courseEntity->price,
                    'price_old' => $courseEntity->price_old,
                    'price_recurrent' => $courseEntity->price_recurrent,
                    'currency' => $courseEntity->currency?->value,
                    'school_id' => $courseEntity->school?->value,
                    'duration' => $courseEntity->duration,
                    'duration_unit' => $courseEntity->duration_unit?->value,
                    'lessons_amount' => $courseEntity->lessons_amount,
                    'employment' => $courseEntity->employment,
                    'metatag_id' => $metatag->id,
                ]);
            }

            if ($courseEntity->direction) {
                $course->directions()->sync(
                    $courseEntity->direction->value ? [$courseEntity->direction->value] : []
                );
            }

            return $course->id;
        } catch (Throwable $error) {
            $this->addError(
                $courseEntity->school->getLabel()
                . ' | ' . $courseEntity->name
                . ' | ' . $error->getMessage()
            );
        }

        return null;
    }

    /**
     * Получение файла изображения на базе URL.
     *
     * @param string $imageUrl URL файла изображения.
     * @return UploadedFile Файл изображения.
     */
    private function getImage(string $imageUrl): UploadedFile
    {
        $ext = pathinfo($imageUrl, PATHINFO_EXTENSION);
        $name = pathinfo($imageUrl, PATHINFO_BASENAME);
        $path = ImageStore::tmp($ext);
        File::copy($imageUrl, $path);

        return new UploadedFile($path, $name);
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
     * Добавление ID обновленного курса.
     *
     * @param int|string $id ID курса.
     * @return $this
     */
    public function addId(int|string $id): self
    {
        $this->ids[] = $id;

        return $this;
    }

    /**
     * Удаление всех ID обновленных курса.
     *
     * @return $this
     */
    public function clearIds(): self
    {
        $this->ids = [];

        return $this;
    }

    /**
     * Получение всех ID обновленных курсов.
     *
     * @return Parser[]
     */
    public function getIds(): array
    {
        return $this->ids;
    }
}
