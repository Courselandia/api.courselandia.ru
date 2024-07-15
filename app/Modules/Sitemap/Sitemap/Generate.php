<?php
/**
 * Модуль sitemap.xml.
 * Этот модуль содержит все классы для работы с генерацией sitemap.xml.
 *
 * @package App\Modules\Sitemap
 */

namespace App\Modules\Sitemap\Sitemap;

use Carbon\Carbon;
use Config;
use DomDocument;
use DOMElement;
use DOMException;
use Storage;
use App\Models\Error;
use App\Models\Event;
use App\Modules\Course\Imports\Parser;
use App\Modules\Page\Models\Page;
use App\Modules\Sitemap\Sitemap\Parts\PartCategory;
use App\Modules\Sitemap\Sitemap\Parts\PartCourse;
use App\Modules\Sitemap\Sitemap\Parts\PartCourses;
use App\Modules\Sitemap\Sitemap\Parts\PartDirection;
use App\Modules\Sitemap\Sitemap\Parts\PartProfession;
use App\Modules\Sitemap\Sitemap\Parts\PartPublication;
use App\Modules\Sitemap\Sitemap\Parts\PartPublications;
use App\Modules\Sitemap\Sitemap\Parts\PartCollection;
use App\Modules\Sitemap\Sitemap\Parts\PartCollections;
use App\Modules\Sitemap\Sitemap\Parts\PartReview;
use App\Modules\Sitemap\Sitemap\Parts\PartReviews;
use App\Modules\Sitemap\Sitemap\Parts\PartSchool;
use App\Modules\Sitemap\Sitemap\Parts\PartSection;
use App\Modules\Sitemap\Sitemap\Parts\PartSkill;
use App\Modules\Sitemap\Sitemap\Parts\PartStatic;
use App\Modules\Sitemap\Sitemap\Parts\PartTeacher;
use App\Modules\Sitemap\Sitemap\Parts\PartTool;
use App\Modules\Sitemap\Sitemap\Parts\PartPromo;
use App\Modules\Sitemap\Sitemap\Parts\PartPromos;

/**
 * Класс генерации sitemap.xml.
 */
class Generate
{
    use Event;
    use Error;

    /**
     * Лимит количества URL на один файл разбитый на части.
     *
     * @var int
     */
    private const int LIMIT = 200;

    /**
     * Части для генерации.
     *
     * @var array<Part>
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
     * ID обновленных страниц.
     *
     * @var int[]|string[]
     */
    private array $ids = [];

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this
            ->addPart(new PartPromos())
            ->addPart(new PartPromo())
            ->addPart(new PartStatic())
            ->addPart(new PartCollections())
            ->addPart(new PartCollection())
            ->addPart(new PartPublications())
            ->addPart(new PartPublication())
            ->addPart(new PartSection())
            ->addPart(new PartCourses())
            ->addPart(new PartReviews())
            ->addPart(new PartDirection())
            ->addPart(new PartCategory())
            ->addPart(new PartProfession())
            ->addPart(new PartSchool())
            ->addPart(new PartSkill())
            ->addPart(new PartTeacher())
            ->addPart(new PartTool())
            ->addPart(new PartCourse())
            ->addPart(new PartReview());

        $this->xml = new DomDocument('1.0', 'utf-8');
    }

    /**
     * Генератор файла.
     *
     * @return void
     * @throws DOMException
     */
    public function run(): void
    {
        $this->offLimits();
        $this->clearIds();
        $this->generateRootElement();
        $this->generateUrlTags();
        $this->saveInFile();
        $this->deleteInactivePages();
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
        $this->clearIds();

        foreach ($parts as $part) {
            foreach ($part->generate() as $item) {
                try {
                    $url = $this->getUrlElement($item);
                    $this->root->appendChild($url);
                    $id = $this->saveInDb($item);
                    $this->addId($id);

                    $this->fireEvent('generated', [$item]);
                } catch (DOMException $error) {
                    $this->addError($error);
                }
            }
        }
    }

    /**
     * Создание XML элемента URL.
     *
     * @param Item $item Генерируемый элемент.
     *
     * @return DOMElement Вернет созданный элемент.
     * @throws DOMException
     */
    private function getUrlElement(Item $item): DOMElement
    {
        $url = $this->xml->createElement('url');
        $url->appendChild($this->xml->createElement('loc', Config::get('app.url') . $item->path));
        $url->appendChild($this->xml->createElement('changefreq', $item->changefreq));
        $url->appendChild($this->xml->createElement('priority', $item->priority));

        if ($item->lastmod) {
            $url->appendChild($this->xml->createElement('lastmod', $item->lastmod->format('Y-m-d')));
        }

        return $url;
    }

    /**
     * Сохранение страницы в базе данных.
     *
     * @param Item $item Генерируемый элемент.
     *
     * @return int|string Вернет ID страницы.
     */
    private function saveInDb(Item $item): int|string
    {
        $page = Page::where('path', $item->path)
            ->first();

        if (!$page) {
            $page = new Page();
        }

        $page->path = $item->path;
        $page->lastmod = $item->lastmod ?? Carbon::now();
        $page->save();

        return $page->getKey();
    }

    /**
     * Сохранение результата генерации в файл sitemap.xml.
     *
     * @return void
     * @throws DOMException
     */
    private function saveInFile(): void
    {
        $this->saveInOneFile();
        $this->saveInPartFiles();
    }

    /**
     * Сохранение в один файл.
     *
     * @return void
     */
    private function saveInOneFile(): void
    {
        $this->xml->formatOutput = true;
        $path = Storage::drive('public-root')->path('sitemap.xml');
        $this->xml->save($path);
    }

    /**
     * Сохранение URL'ов в несколько файлов разбитых по частям.
     *
     * @return void
     * @throws DOMException
     */
    private function saveInPartFiles(): void
    {
        $items = $this->root->childNodes->getIterator();
        $chunks = collect($items)->chunk(self::LIMIT)->toArray();
        $i = 0;

        $files = Storage::drive('public-root')->allFiles('sitemaps');
        Storage::drive('public-root')->delete($files);

        foreach ($chunks as $chunk) {
            $xml = new DomDocument('1.0', 'utf-8');
            $root = $xml->createElement('urlset');
            $root->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');
            $xml->appendChild($root);

            foreach ($chunk as $item) {
                $url = $xml->createElement('url');

                foreach ($item->childNodes as $child) {
                    $url->appendChild($xml->createElement($child->tagName, $child->nodeValue));
                }

                $root->appendChild($url);
            }

            $xml->formatOutput = true;
            $path = Storage::drive('public-root')->path('sitemaps/' . $i . '.xml');
            $xml->save($path);

            $i++;
        }
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

    /**
     * Добавление ID обновленной страницы.
     *
     * @param int|string $id ID страницы.
     * @return $this
     */
    private function addId(int|string $id): self
    {
        $this->ids[] = $id;

        return $this;
    }

    /**
     * Удаление всех ID обновленных страниц.
     *
     * @return $this
     */
    private function clearIds(): self
    {
        $this->ids = [];

        return $this;
    }

    /**
     * Получение всех ID обновленных страниц.
     *
     * @return Parser[]
     */
    private function getIds(): array
    {
        return $this->ids;
    }

    /**
     * Удаление всех не активных страниц.
     *
     * @return void
     */
    private function deleteInactivePages(): void
    {
        Page::whereNotIn('id', $this->getIds())
            ->delete();
    }
}
