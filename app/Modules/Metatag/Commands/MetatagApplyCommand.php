<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Metatag\Commands;

use App\Modules\Metatag\Apply\Apply;
use App\Modules\Metatag\Models\Metatag;
use Illuminate\Console\Command;

/**
 * Заполнение тестовыми курсами базу данных.
 */
class MetatagApplyCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'metatag:apply
        {--clean : Предварительно удалить все метатэги}
        {--update : Только обновить метатэги на основе уже установленных шаблонов}
    ';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Производит заполнение мэтатегами всего каталога.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        if (!$this->confirm('Данная команда приведет к удалению всех старых метатегов. Вы точно хотите продолжить? [да|нет]')) {
            return;
        }

        $apply = new Apply();
        $this->line('Генерация метатэгов.');

        if ($this->option('clean')) {
            Metatag::truncate();
        }

        if ($this->option('update')) {
            $apply->onlyUpdate(true);
        }

        $bar = $this->output->createProgressBar($apply->count());
        $bar->start();

        $apply->addEvent('read', function () use ($bar) {
            $bar->advance();
        });

        $apply->do();
        $bar->finish();
        $this->info("\n\nГенерация метатэгов окончена.");

        if ($apply->hasError()) {
            $errors = [];

            foreach ($apply->getErrors() as $error) {
                $errors[] = $error->getMessage();
            }

            $this->error(implode("\n", $errors));
        }
    }
}
