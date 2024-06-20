<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Imports;

use Util;
use File;
use Cache;
use Typography;
use Throwable;
use ImageStore;
use App\Models\Error;
use Mimey\MimeTypes;
use App\Models\Event;
use App\Modules\Metatag\Data\MetatagSet;
use Illuminate\Http\UploadedFile;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Enums\Currency;
use App\Modules\Metatag\Template\Template;
use App\Modules\Course\Entities\ParserCourse;
use App\Modules\Course\Entities\Course as CourseEntity;
use App\Modules\Metatag\Actions\MetatagSetAction;
use App\Modules\Course\Imports\Parsers\ParserNetology;
use App\Modules\Course\Imports\Parsers\ParserGeekBrains;
use App\Modules\Course\Imports\Parsers\ParserSkillbox;
use App\Modules\Course\Imports\Parsers\ParserSkyPro;
use App\Modules\Course\Imports\Parsers\ParserContented;
use App\Modules\Course\Imports\Parsers\ParserSkillFactory;
use App\Modules\Course\Imports\Parsers\ParserXyzSchool;
use App\Modules\Course\Imports\Parsers\ParserSkillboxEng;
use App\Modules\Course\Imports\Parsers\ParserInternationalSchoolProfessions;
use App\Modules\Course\Imports\Parsers\ParserCoddy;
use App\Modules\Course\Imports\Parsers\ParserEdusonAcademy;
use App\Modules\Course\Imports\Parsers\ParserHexlet;
use App\Modules\Course\Imports\Parsers\ParserOtus;
use App\Modules\Course\Imports\Parsers\ParserBangBangEducation;
use App\Modules\Course\Imports\Parsers\ParserInterra;
use App\Modules\Course\Imports\Parsers\ParserMaed;
use App\Modules\Course\Imports\Parsers\ParserAnoNiidpo;
use App\Modules\Course\Imports\Parsers\ParserNadpo;
use App\Modules\Course\Imports\Parsers\ParserProductstar;
use App\Modules\Course\Imports\Parsers\ParserPentaschool;
use App\Modules\Course\Imports\Parsers\ParserBrunoyam;
use App\Modules\Course\Imports\Parsers\ParserLogomashina;
use App\Modules\Course\Imports\Parsers\ParserSredaObucheniya;
use App\Modules\Course\Imports\Parsers\ParserSfEducation;
use App\Modules\Course\Imports\Parsers\ParserTopAcademy;
use App\Modules\Course\Imports\Parsers\ParserConvertMonster;
use App\Modules\Course\Imports\Parsers\ParserMoscowDigitalSchool;
use App\Modules\Course\Imports\Parsers\ParserKarpovcourses;

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
        $this->addParser(new ParserNetology('https://feeds.advcake.com/feed/download/720bc9eb1b0a9ffdbbfb5a6bb5e1b430'))
            ->addParser(new ParserGeekBrains('https://feeds.advcake.com/feed/download/fb26c1c07ea836c24f519ae06463ad97'))
            ->addParser(new ParserSkillbox('https://feeds.advcake.com/feed/download/04c98be3ff7eb4298b14b863b66f5447'))
            ->addParser(new ParserSkyPro('https://feeds.advcake.com/feed/download/78f9101b00fdc0ee9604c52c1498e8d6'))
            ->addParser(new ParserSkillFactory('https://feeds.advcake.com/feed/download/1b8ef478549c7676fd66df1115ea6197'))
            ->addParser(new ParserContented('https://feeds.advcake.com/feed/download/6d2dd813fb1c90af6a46a09152b8b66e'))
            ->addParser(new ParserXyzSchool('https://feeds.advcake.com/feed/download/beed1d5d836673744f70f869fa8dc96d'))
            ->addParser(new ParserSkillboxEng('https://feeds.advcake.com/feed/download/312e01ad656d54b004998c79a3fbd6d5'))
            ->addParser(new ParserInternationalSchoolProfessions('https://feeds.advcake.com/feed/download/2755679bb42d2521e4a2347bf10bd43b'))
            ->addParser(new ParserEdusonAcademy('https://feeds.advcake.com/feed/download/f6dcb15ae0a559674e0f785786f26582'))
            ->addParser(new ParserCoddy('https://feeds.advcake.com/feed/download/4b6f8a8e2173c49b1204798eb2033a33'))
            ->addParser(new ParserOtus('https://feeds.advcake.com/feed/download/03c2de078b28838db48d2cabf352421e'))
            ->addParser(new ParserHexlet('https://feeds.advcake.com/feed/download/faa81752171de66c811cf1c71bd8b219'))
            ->addParser(new ParserBangBangEducation('https://feeds.advcake.com/feed/download/0475089f1a85e27f985cd2038bdf7222'))
            ->addParser(new ParserInterra('https://feeds.advcake.com/feed/download/f3af68784839de1613e471267e3bc492'))
            ->addParser(new ParserMaed('https://feeds.advcake.com/feed/download/a6128901675d0978230dc93c8801b1c7'))
            ->addParser(new ParserAnoNiidpo('https://feeds.advcake.ru/feed/download/4b819f565960261c7e6a7f286c0030e9?webmaster=04fa7cce'))
            ->addParser(new ParserNadpo('https://feeds.advcake.ru/feed/download/e66e34af4eac3c4a68b2194151def1a1?webmaster=04fa7cce'))
            ->addParser(new ParserProductstar('https://feeds.advcake.ru/feed/download/320facd76b8b26801b94f9356ebc859e?webmaster=04fa7cce'))
            ->addParser(new ParserPentaschool('https://feeds.advcake.ru/feed/download/3477010e0d638e5e1aab7eac144ef20a?webmaster=04fa7cce'))
            ->addParser(new ParserBrunoyam('https://feeds.advcake.ru/feed/download/2b5336d137193d61c32a2e92ab94c90f?webmaster=04fa7cce'))
            ->addParser(new ParserLogomashina('https://feeds.advcake.ru/feed/download/be8c68214fef61cf2638ad0fbe6c836e?webmaster=04fa7cce'))
            ->addParser(new ParserSredaObucheniya('https://feeds.advcake.ru/feed/download/a42eb5254c7878ea6a2266833d7a4da1?webmaster=04fa7cce'))
            ->addParser(new ParserSfEducation('https://feeds.advcake.ru/feed/download/8032b19723134448fe544aa7d2aae2ff?webmaster=04fa7cce'))
            ->addParser(new ParserTopAcademy('https://feeds.advcake.ru/feed/download/8c5d8729b2588c26654404b8015ef7e6?webmaster=04fa7cce'))
            ->addParser(new ParserConvertMonster('https://feeds.advcake.ru/feed/download/0df57ce9bb22354f70cd0ab1722c0ced?webmaster=04fa7cce'))
            ->addParser(new ParserMoscowDigitalSchool('https://feeds.advcake.ru/feed/download/954ad2f3a231b6b458bad33f59abac7d?webmaster=04fa7cce'))
            ->addParser(new ParserKarpovcourses('https://feeds.advcake.ru/feed/download/e62ec80c73849806ae4991ae9510cb37?webmaster=04fa7cce'))
        ;
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
        $this->clearIds();

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

        Cache::tags(['catalog', 'course'])->flush();
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

            $name = html_entity_decode($courseEntity->name);
            $name = rtrim($name, '.');
            $text = html_entity_decode($courseEntity->text);

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
                    'name' => Typography::process($name, true),
                    'link' => $courseEntity->link ?: strtolower(Util::latin(strtolower($name))),
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

                $hasToBeChange = $this->hasToBeChanged(CourseEntity::from($course->toArray()), CourseEntity::from($data));

                if ($this->getReloadImages()) {
                    $image = $courseEntity->image ? $this->getImage($courseEntity->image) : null;

                    $data['image_small_id'] = $image;
                    $data['image_middle_id'] = $image;
                    $data['image_big_id'] = $image;
                }

                if ($hasToBeChange) {
                    $course->update($data);
                }
            } else {
                $template = new Template();

                $templateValues = [
                    'course' => $name,
                    'school' => $courseEntity->school->getLabel(),
                    'price' => $courseEntity->price,
                    'currency' => $courseEntity->currency || Currency::RUB,
                ];

                $templateTitle = 'Курс {course} от {school:genitive} [price:по цене {price}/бесплатно] — Courselandia';
                $templateDescription = 'Приступите к программе обучения прям сейчас онлайн-курса {course} от {school:genitive} выбрав его в каталоге Courselandia, легкий поиск, возможность сравнивать курсы по разным параметрам';
                $headerTemplate = '{course} от {school:genitive}';

                $action = new MetatagSetAction(MetatagSet::from([
                    'title' => Typography::process($template->convert($templateTitle, $templateValues), true),
                    'description' => Typography::process($template->convert($templateDescription, $templateValues), true),
                    'title_template' => $templateTitle,
                    'description_template' => $templateDescription,
                ]));

                $metatag = $action->run();

                $image = $courseEntity->image ? $this->getImage($courseEntity->image) : null;

                $course = Course::create([
                    'uuid' => $courseEntity->uuid,
                    'name' => Typography::process($name, true),
                    'header' => Typography::process($template->convert($headerTemplate, $templateValues), true),
                    'header_template' => $headerTemplate,
                    'link' => $courseEntity->link ?: strtolower(Util::latin(strtolower($name))),
                    'text' => Typography::process($text),
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
     * @return UploadedFile|null Файл изображения.
     */
    private function getImage(string $imageUrl): ?UploadedFile
    {
        $ext = pathinfo($imageUrl, PATHINFO_EXTENSION);
        $name = pathinfo($imageUrl, PATHINFO_BASENAME);

        if (stristr($ext, '?')) {
            $ext = explode('?', $ext)[0];
        }

        if (!$ext) {
            $status = @file_get_contents($imageUrl);

            if (!$status) {
                return null;
            }

            $contentType = null;

            foreach ($http_response_header as $value) {
                if (preg_match_all('/content-type\s*:\s*(.*)$/mi', $value, $matches)) {
                    $contentType = end($matches[1]);
                }
            }

            if ($contentType) {
                $mimes = new MimeTypes();
                $ext = $mimes->getExtension($contentType);
                $name = 'img-tmp.' . $ext;
            }
        }

        if ($name && ($ext === 'jpg' || $ext === 'gif' || $ext === 'png' || $ext === 'webp' || $ext === 'swg')) {
            $path = ImageStore::tmp($ext);

            try {
                File::copy($imageUrl, $path);
            } catch (Throwable) {
                return null;
            }

            if (stristr($name, '?')) {
                $name = explode('?', $name)[0];
            }

            return new UploadedFile($path, $name);
        }

        return null;
    }

    /**
     * Проверка нужно ли обновлять курс.
     *
     * @param CourseEntity $sourceCourse Изначальный курс.
     * @param CourseEntity $targetCourse Полученный курс после изменений.
     *
     * @return bool Вернет результат проверки.
     */
    private function hasToBeChanged(CourseEntity $sourceCourse, CourseEntity $targetCourse): bool
    {
        return $sourceCourse->name !== $targetCourse->name
            || $sourceCourse->link !== $targetCourse->link
            || $sourceCourse->status !== $targetCourse->status
            || $sourceCourse->url !== $targetCourse->url
            || $sourceCourse->price !== $targetCourse->price
            || $sourceCourse->price_old !== $targetCourse->price_old
            || $sourceCourse->price_recurrent !== $targetCourse->price_recurrent
            || $sourceCourse->currency !== $targetCourse->currency
            || $sourceCourse->school_id !== $targetCourse->school_id
            || $sourceCourse->duration !== $targetCourse->duration
            || $sourceCourse->duration_unit !== $targetCourse->duration_unit
            || $sourceCourse->lessons_amount !== $targetCourse->lessons_amount
            || $sourceCourse->employment !== $targetCourse->employment;
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
    private function addId(int|string $id): self
    {
        $this->ids[] = $id;

        return $this;
    }

    /**
     * Удаление всех ID обновленных курса.
     *
     * @return $this
     */
    private function clearIds(): self
    {
        $this->ids = [];

        return $this;
    }

    /**
     * Получение всех ID обновленных курсов.
     *
     * @return Parser[]
     */
    private function getIds(): array
    {
        return $this->ids;
    }
}
