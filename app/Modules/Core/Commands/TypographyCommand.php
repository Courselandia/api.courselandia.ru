<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Commands;

use Log;
use Illuminate\Console\Command;
use App\Modules\Core\Typography\Typography;

/**
 * Типографирует все тексты на сайте.
 */
class TypographyCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'typography';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Типографирует все тексты на сайте.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $typography = new Typography();

        $total = $typography->getTotal();

        if ($total) {
            $this->line('Запуск заданий на типографирование текстов...');

            $bar = $this->output->createProgressBar($total);
            $bar->start();

            $typography->addEvent('finished', function () use ($bar) {
                $bar->advance();
            });

            $typography->run();
            $bar->finish();

            if ($typography->hasError()) {
                $errors = $typography->getErrors();

                foreach ($errors as $error) {
                    $message = 'Ошибка запуска задания: ' . $error->getMessage();
                    Log::error($message);
                    $this->error($message);
                }
            }

            $this->info("\n\nЗадания на типографирование текстов было выполнено.");
        } else {
            $this->info("\n\nНет заданий для типографирования текстов.");
        }

        Log::info('Запуск типографирование текстов.');
    }
}
