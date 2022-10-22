<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Tests\Feature\Helpers;

use DocumentStore;
use App\Modules\Document\Entities\Document as DocumentEntity;
use App\Modules\Document\Helpers\Document;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use File;

/**
 * Тестирование: Класс помощника для сохранения и получения документов.
 */
class DocumentTest extends TestCase
{
    /**
     * Сохранение документа.
     *
     * @return void
     */
    public function testCreate(): void
    {
        $document = UploadedFile::fake()->create('test.txt', 1000, 'text/plain');

        $result = Document::set('test', $document, function (string $name, UploadedFile $value) {
            $tmp = DocumentStore::tmp($value->getClientOriginalExtension());
            File::copy($value->getPathName(), $tmp);

            DocumentStore::setFolder('test');
            $document = new DocumentEntity();
            $document->path = $tmp;

            return DocumentStore::create($document);
        });

        $this->assertNotNull($result);
    }

    /**
     * Обновление документа.
     *
     * @return void
     */
    public function testUpdate(): void
    {
        $document = UploadedFile::fake()->create('test.txt', 1000, 'text/plain');

        $result = Document::set('test', $document, function (string $name, UploadedFile $value) {
            $tmp = DocumentStore::tmp($value->getClientOriginalExtension());
            File::copy($value->getPathName(), $tmp);

            DocumentStore::setFolder('test');
            $document = new DocumentEntity();
            $document->path = $tmp;

            $result = DocumentStore::create($document);

            return DocumentStore::update($result, $document);
        });

        $this->assertNotNull($result);
    }
}
