<?php
/**
 * Модуль Документов.
 * Этот модуль содержит все классы для работы с документами которые хранятся к записям в базе данных.
 *
 * @package App\Modules\Document
 */

namespace App\Modules\Document\Repositories;

use Exception;
use File;
use Generator;
use App\Models\Repository;
use App\Modules\Document\Entities\Document as DocumentEntity;

/**
 * Абстрактный класс построения репозитория.
 */
abstract class Document extends Repository
{
    /**
     * Документы полученные ранее.
     * Будем хранить данные полученных ранее документов, чтобы снизить нагрузку на систему.
     *
     * @var array
     */
    private static array $documents = [];

    /**
     * Папка для хранения.
     *
     * @var string
     */
    private string $folder;

    /**
     * Получение документа по ее ID из базы ранее полученных документов.
     *
     * @param int|string $id ID документа.
     *
     * @return DocumentEntity|null Сущность документа.
     */
    protected static function getById(int|string $id): ?DocumentEntity
    {
        return self::$documents[$id] ?? null;
    }

    /**
     * Установка данных документа по ее ID в базу ранее полученных документов.
     *
     * @param int|string $id ID документа.
     * @param DocumentEntity $document Сущность документа.
     *
     * @return void
     */
    protected static function setById(int|string $id, DocumentEntity $document): void
    {
        self::$documents[$id] = $document;
    }

    /**
     * Получение всех записей.
     *
     * @param DocumentEntity|null $entity Сущность.
     *
     * @return Generator|DocumentEntity|null Генератор.
     */
    abstract public function all(DocumentEntity $entity = null): Generator|DocumentEntity|null;

    /**
     * Получить количество всех документов.
     *
     * @return int Количество записей.
     */
    abstract public function count(): int;

    /**
     * Создание.
     *
     * @param DocumentEntity $entity Данные для добавления.
     *
     * @return int|string Вернет ID последней вставленной строки.
     */
    abstract public function create(DocumentEntity $entity): int|string;

    /**
     * Обновление.
     *
     * @param int|string $id Id записи для обновления.
     * @param DocumentEntity $entity Данные для добавления.
     *
     * @return int|string Вернет ID вставленной строки.
     */
    abstract public function update(int|string $id, DocumentEntity $entity): int|string;

    /**
     * Обновление байт кода картинки.
     *
     * @param int|string $id Id записи для обновления.
     * @param string $byte Байт код картинки.
     *
     * @return bool Вернет булево значение успешности операции.
     */
    abstract public function updateByte(int|string $id, string $byte): bool;

    /**
     * Удаление.
     *
     * @param int|string|array|null $id Id записи для удаления.
     *
     * @return bool Вернет булево значение успешности операции.
     */
    abstract public function destroy(int|string|array $id = null): bool;

    /**
     * Создание копии документа.
     * Копия создается во временной папке с псевдослучайным названием.
     *
     * @param string $path Путь к документу из которого нужно сделать копию.
     *
     * @return string Возвращает путь к копии.
     * @throws Exception
     */
    public function copy(string $path): string
    {
        $tmp = $this->tmp(pathinfo($path)['extension']);
        File::copy($path, $tmp);

        return $tmp;
    }

    /**
     * Получение пути к файлу для временного документа.
     *
     * @param mixed $format Формат документа в нумерованном виде или текстовом.
     *
     * @return string Путь к временному документу.
     * @throws Exception
     */
    public function tmp(string $format): string
    {
        return storage_path('app/tmp/doc_' . time() . mt_rand(1, 100000) . '.' . $format);
    }

    /**
     * Установка папки хранения.
     *
     * @param string $folder Название папки.
     *
     * @return $this Вернет текущий объект.
     */
    public function setFolder(string $folder): Document
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Получение папки хранения.
     *
     * @return string Вернет название папки.
     */
    public function getFolder(): string
    {
        return $this->folder;
    }
}
