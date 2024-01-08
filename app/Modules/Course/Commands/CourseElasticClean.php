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
 * Удаление индексов в Elasticsearch.
 */
class CourseElasticClean extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'course:elastic-clean';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Удаление индексов в Elasticsearch.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $status = $this->confirm('Вы точно хотите удалить все индексы в Elasticsearch?');

        if ($status) {
            $export = new Export();
            $export->clean();

            $this->info('Индексы были удалены.');

            if ($export->hasError()) {
                $errors = [];

                foreach ($export->getErrors() as $error) {
                    $errors[] = $error->getMessage();
                }

                $this->error(implode("\n", $errors));
            }
        }
    }
}
