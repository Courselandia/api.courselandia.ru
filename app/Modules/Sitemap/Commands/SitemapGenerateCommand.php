<?php
/**
 * Модуль sitemap.xml.
 * Этот модуль содержит все классы для работы с генерацией sitemap.xml.
 *
 * @package App\Modules\Sitemap
 */

namespace App\Modules\Sitemap\Commands;

use App\Modules\Sitemap\Sitemap\Generate;
use DOMException;
use Illuminate\Console\Command;
use Log;

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
     * @throws DOMException
     */
    public function handle(): void
    {
        $sitemap = new Generate();

        $this->line('Генерация файла sitemap.xml.');
        $bar = $this->output->createProgressBar($sitemap->getTotal());
        $bar->start();

        $sitemap->addEvent('generated', function () use ($bar) {
            $bar->advance();
        });

        $sitemap->run();

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
