<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Commands;

use Log;
use App\Modules\Analyzer\Analyze\Analyze;
use Illuminate\Console\Command;

/**
 * Анализ текстов.
 */
class AnalyzerAnalyzeCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'analyzer:analyze';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Запуск анализа текстов.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $analyze = new Analyze();
        $total = $analyze->getTotal();

        if ($total) {
            $this->line('Запуск заданий на анализ текстов...');

            $bar = $this->output->createProgressBar($total);
            $bar->start();

            $analyze->addEvent('run', function () use ($bar) {
                $bar->advance();
            });

            $analyze->run();
            $bar->finish();

            if ($analyze->hasError()) {
                $errors = $analyze->getErrors();

                foreach ($errors as $error) {
                    $message = 'Ошибка запуска задания: ' . $error->getMessage();
                    Log::error($message);
                    $this->error($message);
                }
            }

            $this->info("\n\nЗадания на анализ текстов были отправлены в очередь.");
        } else {
            $this->info("\n\nНет заданий для анализа текстов.");
        }

        Log::info('Запуск анализа текстов.');
    }
}
