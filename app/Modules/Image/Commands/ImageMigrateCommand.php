<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Commands;

use App\Modules\Image\Entities\Image as ImageEntity;
use ErrorException;
use Illuminate\Console\Command;
use ImageStore;
use App;

/**
 * Класс команда миграции изображений.
 * Позволяет мигрировать изображениям из одного драйвера хранения в другой.
 */
class ImageMigrateCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'system:image-migrate {from : Current driver} {to : Another driver}';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Migrate the images from a driver to another driver.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->line('Migrating the images of site...');
        $count = ImageStore::count();
        $bar = $this->output->createProgressBar($count);
        $errors = [];

        foreach (ImageStore::all() as $image) {
            /**
             * @var $image ImageEntity
             */
            $pathSourceFrom = App::make('image.store.driver')->driver($this->argument('from'))->pathSource(
                $image->folder,
                $image->id,
                $image->format,
            );

            try {
                App::make('image.store.driver')->driver($this->argument('to'))->create(
                    $image->folder,
                    $image->id,
                    $image->format,
                    $pathSourceFrom
                );
            } catch (ErrorException $error) {
                $errors[] = $error->getMessage();
            }
        }

        $bar->finish();
        $this->info("\n\nThe migration of images has been successfully completed.");

        if (count($errors)) {
            $this->warn("\nWarn: \n".implode("\n", $errors));
        }
    }
}
