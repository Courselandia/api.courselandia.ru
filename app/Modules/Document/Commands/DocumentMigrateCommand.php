<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Commands;

use App\Modules\Document\Entities\Document as DocumentEntity;
use ErrorException;
use Illuminate\Console\Command;
use DocumentStore;
use App;

/**
 * Класс команда миграции документов.
 * Позволяет мигрировать документам из одного драйвера хранения в другой.
 */
class DocumentMigrateCommand extends Command
{
    /**
     * Название консольной команды.
     *
     * @var string
     */
    protected $signature = 'system:document-migrate {from : Current driver} {to : Another driver}';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Migrate the documents from a driver to another driver.';

    /**
     * Выполнение команды.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->line('Migrating the documents of site...');
        $count = DocumentStore::count();
        $bar = $this->output->createProgressBar($count);
        $errors = [];

        foreach (DocumentStore::all() as $document) {
            /**
             * @var $document DocumentEntity
             */
            $pathSourceFrom = App::make('document.store.driver')->driver($this->argument('from'))->pathSource(
                $document->folder,
                $document->id,
                $document->format,
            );

            try {
                App::make('document.store.driver')->driver($this->argument('to'))->create(
                    $document->folder,
                    $document->id,
                    $document->format,
                    $pathSourceFrom
                );
            } catch (ErrorException $error) {
                $errors[] = $error->getMessage();
            }
        }

        $bar->finish();
        $this->info("\n\nThe migration of documents has been successfully completed.");

        if (count($errors)) {
            $this->warn("\nWarn: \n".implode("\n", $errors));
        }
    }
}
