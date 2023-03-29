<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Commands;

use Log;
use App\Modules\Core\Sitemap\Sitemap;
use Illuminate\Console\Command;

/**
 * Генерация файла sitemap.xml.
 */
class SitemapGenerateCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'sitemap:generate';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Генерация файла sitemap.xml.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $sitemap = new Sitemap();

        $this->line('Генерация файла sitemap.xml.');
        $bar = $this->output->createProgressBar($sitemap->getTotal());
        $bar->start();

        $sitemap->addEvent('generated', function () use ($bar) {
            $bar->advance();
        });

        $sitemap->generate();

        $bar->finish();

        if ($sitemap->hasError()) {
            $errors = $sitemap->getErrors();

            foreach ($errors as $error) {
                $message = 'Ошибка генерации sitemap.xml: ' . $error->getMessage();
                Log::error($message);
                $this->error($message);
            }
        }

        $this->info("\n\nГенерация файла sitemap.xml закончена.");
        Log::info('Генерация sitemap.xml.');
    }
}
