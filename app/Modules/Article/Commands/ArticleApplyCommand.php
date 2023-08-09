<?php
/**
 * Статьи написанные искусственным интеллектом для разных сущностей.
 * Пакет содержит классы для хранения статей написанных искусственным интеллектом.
 *
 * @package App.Models.Article
 */

namespace App\Modules\Article\Commands;

use Log;
use App\Modules\Article\Apply\Apply;
use Illuminate\Console\Command;

/**
 * Принятия всех текстов.
 */
class ArticleApplyCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'article:apply
        {--unique= : Переписывать если уникальность ниже данного показателя}
        {--water= : Переписывать если количество воды выше данного показателя}
        {--spam= : Переписывать если заспамленность выше данного показателя}
    ';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Запуск принятия всех написанных текстов.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $apply = new Apply($this->option('unique'), $this->option('water'), $this->option('spam'));
        $total = $apply->total();

        if ($total) {
            $this->line('Запуск заданий на принятия текстов...');

            $bar = $this->output->createProgressBar($total);
            $bar->start();

            $apply->addEvent('apply', function () use ($bar) {
                $bar->advance();
            });

            $apply->run();
            $bar->finish();

            if ($apply->hasError()) {
                $errors = $apply->getErrors();

                foreach ($errors as $error) {
                    $message = 'Ошибка исполнения: ' . $error->getMessage();
                    Log::error($message);
                    $this->error($message);
                }
            }

            $this->info("\n\nЗадания на принятия текстов были запущены.");
        } else {
            $this->info("\n\nНет текстов для принятия.");
        }

        Log::info('Запуск заданий на принятия текстов.');
    }
}
