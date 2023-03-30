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
use DOMElement;
use DOMException;
use Storage;
use App\Models\Error;
use App\Models\Event;
use App\Modules\Core\Sitemap\Parts\PartDirection;
use App\Modules\Core\Sitemap\Parts\PartCategory;
use App\Modules\Core\Sitemap\Parts\PartProfession;
use App\Modules\Core\Sitemap\Parts\PartSchool;
use App\Modules\Core\Sitemap\Parts\PartSkill;
use App\Modules\Core\Sitemap\Parts\PartTeacher;
use App\Modules\Core\Sitemap\Parts\PartTool;
use App\Modules\Core\Sitemap\Parts\PartCourse;

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
     * Корневой элемент.
     *
     * @var DOMElement
     */
    private DOMElement $root;

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this
            ->addPart(new PartDirection())
            ->addPart(new PartCategory())
            ->addPart(new PartProfession())
            ->addPart(new PartSchool())
            ->addPart(new PartSkill())
            ->addPart(new PartTeacher())
            ->addPart(new PartTool())
            ->addPart(new PartCourse());

        $this->xml = new DomDocument('1.0', 'utf-8');
    }

    /**
     * Генератор файла.
     */
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
     * @return int Общее количество генерируемых элементов в файле
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
            $urlset = $this->xml->createElement('urlset');
            $urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
            $this->xml->appendChild($urlset);

            $this->root = $urlset;
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
                    $url = $this->xml->createElement('url');
                    $url->appendChild($this->xml->createElement('loc', Config::get('app.api_url') . $item->path));
                    $url->appendChild($this->xml->createElement('changefreq', $item->changefreq));
                    $url->appendChild($this->xml->createElement('priority', $item->priority));

                    $this->root->appendChild($url);

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
