<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Commands;

use Log;
use App\Modules\Article\Rewrite\Rewrite;
use Illuminate\Console\Command;

/**
 * Переписывание текстов.
 */
class ArticleRewriteCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'article:rewrite
        {--unique= : Переписывать если уникальность ниже данного показателя}
        {--water= : Переписывать если количество воды выше данного показателя}
        {--spam= : Переписывать если заспамленность выше данного показателя}';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Запуск переписывание текстов искусственным интеллектом.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $rewrite = new Rewrite($this->option('unique'), $this->option('water'), $this->option('spam'));
        $total = $rewrite->getTotal();

        if ($total) {
            if (!$this->confirm('Вы действительно хотите запустить переписывание статей в количестве: ' . $total . '?')) {
                return;
            }

            $this->line('Запуск заданий на переписывание текстов...');

            $bar = $this->output->createProgressBar($total);
            $bar->start();

            $rewrite->addEvent('run', function () use ($bar) {
                $bar->advance();
            });

            $rewrite->run();
            $bar->finish();

            if ($rewrite->hasError()) {
                $errors = $rewrite->getErrors();

                foreach ($errors as $error) {
                    $message = 'Ошибка запуска задания: ' . $error->getMessage();
                    Log::error($message);
                    $this->error($message);
                }
            }

            $this->info("\n\nЗадания на переписывание текстов были отправлены в очередь.");
        } else {
            $this->info("\n\nНет заданий для переписывание текстов.");
        }

        Log::info('Запуск переписывание текстов.');
    }
}
