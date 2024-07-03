<?php
/**
 * Модуль Изображения.
 * Этот модуль содержит все классы для работы с изображениями которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Image
 */

namespace App\Modules\Image\Commands;

use Log;
use App\Modules\Image\Normalize\Normalize;
use Illuminate\Console\Command;

/**
 * Нормализация изображений.
 */
class ImageNormalizeCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'image:normalize';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Нормализация изображений.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->line('Начинаем нормализацию...');

        $normalize = new Normalize();
        $bar = $this->output->createProgressBar($normalize->getTotal());
        $bar->start();

        $normalize->addEvent('normalized', function () use ($bar) {
            $bar->advance();
        });

        $normalize->run();
        $bar->finish();

        $this->info("\n");

        if ($normalize->hasError()) {
            $errors = $normalize->getErrors();

            foreach ($errors as $error) {
                $message = 'Ошибка нормализации изображений: ' . $error->getMessage();
                Log::error($message);
                $this->error($message);
            }
        }

        $this->info("\nНормализация была завершена.");
        Log::info('Нормализация изображений.');
    }
}
