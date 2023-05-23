<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Commands;

use Log;
use App\Modules\Article\Write\Write;
use Illuminate\Console\Command;

/**
 * Написание текстов.
 */
class ArticleWriteCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'article:write';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Запуск написания текстов искусственным интеллектом.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $write = new Write();
        $total = $write->getTotal();

        if ($total) {
            $this->line('Запуск заданий на написание текстов...');

            $bar = $this->output->createProgressBar($total);
            $bar->start();

            $write->addEvent('run', function () use ($bar) {
                $bar->advance();
            });

            $write->run();
            $bar->finish();

            if ($write->hasError()) {
                $errors = $write->getErrors();

                foreach ($errors as $error) {
                    $message = 'Ошибка запуска задания: ' . $error->getMessage();
                    Log::error($message);
                    $this->error($message);
                }
            }

            $this->info("\n\nЗадания на написания текстов были отправлены в очередь.");
        } else {
            $this->info("\n\nНет заданий для написания текстов.");
        }

        Log::info('Запуск написания текстов.');
    }
}
