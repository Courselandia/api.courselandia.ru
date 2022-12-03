<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Imports;

use Cache;
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
        $this->addParser(new ParserNetology());
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
            $this->disableCourses($parser->getSchool()->value);

            foreach ($parser->read() as $courseEntity) {
                $create = $this->save($courseEntity);

                $this->fireEvent(
                    'read',
                    [
                        $courseEntity,
                        $create
                    ]
                );
            }

            if ($parser->hasError()) {
                $errors = $parser->getErrors();

                foreach ($errors as $error) {
                    $this->addError($error);
                }
            }
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
     * Отключение курсов школы.
     *
     * @param int|string $schoolId ID школы.
     * @return void
     */
    private function disableCourses(int|string $schoolId): void
    {
        Course::select('id')
            ->where('school_id', $schoolId)
            ->update([
                'status' => Status::DISABLED->value
            ]);
    }

    /**
     * Сохранение курса.
     *
     * @param ParserCourse $courseEntity Курс.
     *
     * @return bool Вернет true, если сохранение, или false, если обновление.
     */
    private function save(ParserCourse $courseEntity): bool
    {
        $course = Course::where('uuid', $courseEntity->uuid)
            ->first();

        if ($course) {
            $course->update([
                'name' => $courseEntity->name,
                'status' => $courseEntity->status ? Status::ACTIVE->value : Status::DISABLED->value,
                'url' => $courseEntity->url,
                'price' => $courseEntity->price,
                'currency' => $courseEntity->currency?->value,
                'school' => $courseEntity->school?->value,
                'duration' => $courseEntity->duration,
                'duration_unit' => $courseEntity->duration_unit?->value,
            ]);

            if (count($course->directions) === 0 && $courseEntity->direction) {
                $course->directions()->sync(
                    $courseEntity->direction->value ? [$courseEntity->direction->value] : []
                );
            }

            return false;
        } else {
            $image = $courseEntity->image ? $this->getImage($courseEntity->image) : null;

            $course = Course::create([
                'uuid' => $courseEntity->uuid,
                'name' => $courseEntity->name,
                'link' => Util::latin($courseEntity->name),
                'header' => $courseEntity->name,
                'text' => $courseEntity->text,
                'status' => $courseEntity->status ? Status::DRAFT->value : Status::DISABLED->value,
                'url' => $courseEntity->url,
                'image_small_id' => $image,
                'image_middle_id' => $image,
                'image_big_id' => $image,
                'price' => $courseEntity->price,
                'currency' => $courseEntity->currency?->value,
                'school_id' => $courseEntity->school?->value,
                'duration' => $courseEntity->duration,
                'duration_unit' => $courseEntity->duration_unit?->value,
            ]);

            if ($courseEntity->direction) {
                $course->directions()->sync(
                    $courseEntity->direction->value ? [$courseEntity->direction->value] : []
                );
            }

            return true;
        }
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
}
