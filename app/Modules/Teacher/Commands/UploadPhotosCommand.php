<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Commands;

use App\Modules\Teacher\Upload\Photo;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use App\Models\Exceptions\ResponseException;

/**
 * Нормализация каталога курсов.
 */
class UploadPhotosCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'teacher:upload-photos';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Загрузка фотографий с источника.';

    /**
     * Выполнение команды.
     *
     * @return void
     * @throws ResponseException
     * @throws GuzzleException
     */
    public function handle(): void
    {
        $this->line('Начинаем грабинг...');

        $photoUpload = new Photo();

        $bar = $this->output->createProgressBar($photoUpload->getCount());
        $bar->start();

        $photoUpload->addEvent('put', function () use ($bar) {
            $bar->advance();
        });

        $photoUpload->run();

        $bar->finish();

        if ($photoUpload->hasError()) {
            $errors = $photoUpload->getErrors();

            foreach ($errors as $error) {
                $message = 'Ошибка загрузки фотографий: ' . $error->getMessage();
                $this->error($message);
            }
        }

        $this->info("\n\nГрабинг фотографий завершен.");
    }
}
