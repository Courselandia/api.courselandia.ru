<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Commands;

use Log;
use App\Modules\Teacher\Normalize\Normalize;
use Illuminate\Console\Command;

/**
 * Нормализация учителей.
 */
class TeacherNormalizeCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'teacher:normalize';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Нормализация учителей.';

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

        if ($normalize->hasError()) {
            $errors = $normalize->getErrors();

            foreach ($errors as $error) {
                $message = 'Ошибка нормализации учителя: ' . $error->getMessage();
                Log::error($message);
                $this->error($message);
            }
        }

        $this->info("\n\nНормализация была завершена.");
        Log::info('Нормализация учителей.');
    }
}
