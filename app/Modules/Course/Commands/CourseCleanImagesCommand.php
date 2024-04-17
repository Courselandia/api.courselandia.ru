<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Commands;

use Storage;
use Illuminate\Console\Command;
use App\Modules\Image\Models\ImageMongoDb;
use App\Modules\Course\Models\Course;

/**
 * Удаление ненужных изображений курсов.
 */
class CourseCleanImagesCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'course:images-clean';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Удаление ненужных изображений курсов.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $images = ImageMongoDb::where('folder', 'courses')
            ->get();

        $bar = $this->output->createProgressBar(count($images));
        $bar->start();
        $deleted = 0;

        foreach ($images as $image) {
            $course = Course::withTrashed()
                ->where(function ($query) use ($image) {
                    $query->where('image_small_id', $image->id)
                        ->orWhere('image_middle_id', $image->id)
                        ->orWhere('image_big_id', $image->id);
                })
                ->first();

            if (!$course) {
                $path = 'images/courses/' . $image->id . '.' . $image->format;
                Storage::drive('public')->delete($path);
                $image->forceDelete();
                $deleted++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->line("\n\nКоличество удаленных файлов: " . $deleted);
        $this->info("\nНенужные файлы были удалены...");
    }
}
