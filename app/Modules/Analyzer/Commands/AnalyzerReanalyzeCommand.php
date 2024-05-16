<?php
/**
 * Анализатор текстов для SEO проверки.
 * Пакет содержит классы для хранения результатов анализа текстов для SEO.
 *
 * @package App.Models.Analyzer
 */

namespace App\Modules\Analyzer\Commands;

use Log;
use Illuminate\Console\Command;
use App\Modules\Analyzer\Analyze\Reanalyze;

/**
 * Анализ текстов.
 */
class AnalyzerReanalyzeCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'analyzer:reanalyze
        {--categories=* : Список категорий для фильтрации.}
        {--statuses=* : Список статусов для фильтрации.}
        {--active= : Признак того, что сущность активна или не активна.}
        {--unique= : Проводить анализ если уникальность ниже данного показателя}
        {--water= : Проводить анализ если количество воды выше данного показателя}
        {--spam= : Проводить анализ если заспамленность выше данного показателя}
    ';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Запуск анализа текста.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $reanalyze = new Reanalyze(
            $this->option('categories'),
            $this->option('statuses'),
            $this->option('active'),
            $this->option('unique'),
            $this->option('water'),
            $this->option('spam'),
        );
        $total = $reanalyze->getTotal();

        if ($total) {
            $confirm = $this->confirm('Вы точно хотите запустить повторный анализ для ' . $total . ' записей?');

            if (!$confirm) {
                return;
            }

            $this->line('Запуск заданий на повторный анализ текстов...');

            $bar = $this->output->createProgressBar($total);
            $bar->start();

            $reanalyze->addEvent('run', function () use ($bar) {
                $bar->advance();
            });

            $reanalyze->run();
            $bar->finish();

            if ($reanalyze->hasError()) {
                $errors = $reanalyze->getErrors();

                foreach ($errors as $error) {
                    $message = 'Ошибка запуска задания: ' . $error->getMessage();
                    Log::error($message);
                    $this->error($message);
                }
            }

            $this->info("\n\nЗадания на повторный анализ текста были отправлены в очередь.");

            Log::info('Запуск повторного анализа текста.');
        } else {
            $this->info("\n\nНет заданий для повторного анализа текста.");
        }
    }
}
