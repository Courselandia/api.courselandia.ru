<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Commands;

use App\Modules\Course\Elastic\Export;
use Illuminate\Console\Command;

/**
 * Экспорт курсов в Elasticsearch.
 */
class CourseElastic extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'course:elastic';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Экспортирование курсов в Elasticsearch.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->line('Экспортирование курсов в Elasticsearch...');

        $export = new Export();

        $bar = $this->output->createProgressBar($export->count());
        $bar->start();

        $export->addEvent('export', function () use ($bar) {
            $bar->advance();
        });

        $export->run();
        $bar->finish();
        $this->info("\n\nЭкспортирование курсов в Elasticsearch завершено.");

        if ($export->hasError()) {
            $errors = [];

            foreach ($export->getErrors() as $error) {
                $errors[] = $error->getMessage();
            }

            $this->error(implode("\n", $errors));
        }
    }
}
