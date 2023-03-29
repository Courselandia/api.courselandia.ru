<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Sitemap;

use Config;
use DomDocument;
use DOMException;
use Storage;
use App\Models\Error;
use App\Models\Event;
use App\Modules\Core\Sitemap\Parts\PartDirection;

/**
 * Класс генерации sitemap.xml.
 */
class Sitemap
{
    use Event;
    use Error;

    /**
     * Части для генерации.
     *
     * @var array
     */
    private array $parts = [];

    /**
     * Генерируемый файл.
     *
     * @var DomDocument
     */
    private DomDocument $xml;

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->addPart(new PartDirection());
        $this->xml = new DomDocument('1.0', 'utf-8');
    }

    public function generate(): void
    {
        $this->offLimits();
        $this->generateRootElement();
        $this->generateUrlTags();
        $this->saveInFile();
    }

    /**
     * Получить количество генерируемых элементов.
     *
     * @return void
     */
    public function getTotal(): int
    {
        $parts = $this->getParts();
        $total = 0;

        foreach ($parts as $part) {
            $total += $part->count();
        }

        return $total;
    }

    /**
     * Отключение лимитов.
     *
     * @return void
     */
    private function offLimits(): void
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        ignore_user_abort(true);
    }

    /**
     * Генерация корневого элемента XML файла: urlset.
     *
     * @return void
     */
    private function generateRootElement(): void
    {
        try {
            $this->xml->appendChild(
                $this->xml->createElement('urlset')
                    ->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9')
            );
        } catch (DOMException $error) {
            $this->addError($error);
        }
    }

    /**
     * Генерация всех тегов URL файла sitemap.xml.
     *
     * @return void
     */
    private function generateUrlTags(): void
    {
        $parts = $this->getParts();

        foreach ($parts as $part) {
            foreach ($part->generate() as $item) {
                try {
                    $this->xml->appendChild(
                        $this->xml->createElement('url')
                            ->appendChild($this->xml->createElement('loc', Config::get('app.api_url' . $item->path)))
                            ->appendChild($this->xml->createElement('changefreq', $item->changefreq))
                            ->appendChild($this->xml->createElement('priority', $item->priority))
                    );

                    $this->fireEvent('generated', [$item]);
                } catch (DOMException $error) {
                    $this->addError($error);
                }
            }
        }
    }

    /**
     * Сохранение результата генерации в файл sitemap.xml.
     *
     * @return void
     */
    private function saveInFile(): void
    {
        $this->xml->formatOutput = true;
        $path = Storage::drive('public-root')->path('sitemap.xml');
        $this->xml->save($path);
    }

    /**
     * Добавление части для генерации.
     *
     * @param Part $part Часть для генерации.
     * @return $this
     */
    public function addPart(Part $part): self
    {
        $this->parts[] = $part;

        return $this;
    }

    /**
     * Удаление частей для генерации.
     *
     * @return $this
     */
    public function clearParts(): self
    {
        $this->parts = [];

        return $this;
    }

    /**
     * Получение всех частей для генерации.
     *
     * @return Part[]
     */
    public function getParts(): array
    {
        return $this->parts;
    }
}
