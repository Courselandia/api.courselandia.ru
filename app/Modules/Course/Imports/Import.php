<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Imports;

use App\Modules\Course\Imports\Parsers\ParserGeekBrains;
use Cache;
use Throwable;
use Util;
use File;
use ImageStore;
use App\Models\Error;
use App\Modules\Course\Entities\ParserCourse;
use App\Models\Event;
use App\Modules\Course\Imports\Parsers\ParserNetology;
use App\Modules\Course\Models\Course;
use App\Modules\Course\Enums\Status;
use Illuminate\Http\UploadedFile;

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
     * Конструктор.
     */
    public function __construct()
    {
        $this->addParser(new ParserNetology())
            ->addParser(new ParserGeekBrains());
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

            Course::whereNotIn('id', $this->getIds())
                ->update([
                    'status' => Status::DISABLED->value
                ]);
        }

        Cache::tags([
            'course',
            'direction',
            'profession',
            'category',
            'skill',
            'teacher',
            'tool',
            'review',
        ])->flush();
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
        //try {
            $course = Course::where('uuid', $courseEntity->uuid)
                ->first();

            if ($course) {
                $course->update([
                    'header' => $courseEntity->header,
                    'link' => Util::latin(strtolower($courseEntity->header)),
                    'status' => $courseEntity->status ? $course->status : Status::DISABLED->value,
                    'url' => $courseEntity->url,
                    'price' => $courseEntity->price,
                    'price_old' => $courseEntity->price_old,
                    'price_recurrent_price' => $courseEntity->price_recurrent_price,
                    'currency' => $courseEntity->currency?->value,
                    'school' => $courseEntity->school?->value,
                    'duration' => $courseEntity->duration,
                    'duration_unit' => $courseEntity->duration_unit?->value,
                    'lessons_amount' => $courseEntity->lessons_amount,
                ]);
            } else {
                $image = $courseEntity->image ? $this->getImage($courseEntity->image) : null;

                $course = Course::create([
                    'uuid' => $courseEntity->uuid,
                    'header' => $courseEntity->header,
                    'link' => Util::latin(strtolower($courseEntity->header)),
                    'text' => $courseEntity->text,
                    'status' => $courseEntity->status ? Status::DRAFT->value : Status::DISABLED->value,
                    'url' => $courseEntity->url,
                    'image_small_id' => $image,
                    'image_middle_id' => $image,
                    'image_big_id' => $image,
                    'price' => $courseEntity->price,
                    'price_old' => $courseEntity->price_old,
                    'price_recurrent_price' => $courseEntity->price_recurrent_price,
                    'currency' => $courseEntity->currency?->value,
                    'school_id' => $courseEntity->school?->value,
                    'duration' => $courseEntity->duration,
                    'duration_unit' => $courseEntity->duration_unit?->value,
                    'lessons_amount' => $courseEntity->lessons_amount,
                ]);
            }

            if (count($course->directions) === 0 && $courseEntity->direction) {
                $course->directions()->sync(
                    $courseEntity->direction->value ? [$courseEntity->direction->value] : []
                );
            }

            return $course->id;
        /*} catch (Throwable $error) {
            $this->addError(
                $courseEntity->school->getLabel()
                . ' | ' . $courseEntity->header
                . ' | ' . $error->getMessage()
            );
        }*/

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
