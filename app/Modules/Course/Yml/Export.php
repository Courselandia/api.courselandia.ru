<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Yml;

use Generator;
use Storage;
use Cache;
use Config;
use App\Models\Event;
use App\Models\Error;
use DOMDocument;
use DOMElement;
use Carbon\Carbon;
use DOMException;
use Throwable;
use App\Modules\Course\Enums\Duration;
use App\Modules\Course\Enums\Status;
use App\Modules\Course\Models\Course;
use App\Modules\Direction\Enums\Direction;
use Illuminate\Database\Eloquent\Builder;

/**
 * Экспортирование курсов в формате YML.
 */
class Export
{
    use Event;
    use Error;

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
        $this->xml = new DomDocument('1.0', 'utf-8');
    }

    /**
     * Запуск экспорта.
     *
     * @throws DOMException
     */
    public function run(): void
    {
        Cache::flush();

        $this->offLimits();
        $this->exports();
    }

    /**
     * Получение количества экспортируемых курсов.
     *
     * @return int Количество курсов.
     */
    public function getTotal(): int
    {
        return $this->getQuery()->count();
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
     * Экспортирование курсов.
     *
     * @return void
     * @throws DOMException
     */
    private function exports(): void
    {
        $this->generateRootElement();
        $this->generateSchoolElement();
        $this->generateCurrenciesElement();
        $this->generateOffersElement();
        $this->saveInFile();
    }

    /**
     * Генерация корневого элемента XML файла: yml_catalog.
     *
     * @return void
     */
    private function generateRootElement(): void
    {
        try {
            $root = $this->xml->createElement('yml_catalog');
            $root->setAttribute('date', Carbon::now()->format('Y-m-d H:i:s'));
            $this->xml->appendChild($root);

            $this->root = $root;
        } catch (DOMException $error) {
            $this->addError($error);
        }
    }

    /**
     * Генерация элемента XML файла: school.
     *
     * @return void
     */
    private function generateSchoolElement(): void
    {
        try {
            $schoolEntity = $this->getSchool();
            $shop = $this->xml->createElement('shop');
            $shop->appendChild($this->xml->createElement('name', $schoolEntity->name));
            $shop->appendChild($this->xml->createElement('company', $schoolEntity->company));
            $shop->appendChild($this->xml->createElement('url', $schoolEntity->url));
            $shop->appendChild($this->xml->createElement('email', $schoolEntity->email));
            $shop->appendChild($this->xml->createElement('picture', $schoolEntity->picture));
            $shop->appendChild($this->xml->createElement('description', $schoolEntity->description));
            $this->root->appendChild($shop);
        } catch (DOMException $error) {
            $this->addError($error);
        }
    }

    /**
     * Генерация элемента XML файла: offers.
     *
     * @return void
     * @throws DOMException
     */
    private function generateOffersElement(): void
    {
        $offers = $this->xml->createElement('offers');

        foreach ($this->getOffer() as $offerEntity) {
            try {
                $offer = $this->xml->createElement('offer');
                $offer->setAttribute('id', $offerEntity->id);
                $offer->appendChild($this->xml->createElement('name', $this->removeSpecSymbols($offerEntity->name)));
                $offer->appendChild($this->xml->createElement('url', $offerEntity->url));
                $offer->appendChild($this->xml->createElement('categoryId', $offerEntity->categoryId));
                $offer->appendChild($this->xml->createElement('currencyId', $offerEntity->currencyId));
                $offer->appendChild($this->xml->createElement('picture', $offerEntity->picture));
                $offer->appendChild($this->xml->createElement('description', $this->removeSpecSymbols($this->removeHtml($offerEntity->description))));

                if ($offerEntity->price_recurrent) {
                    $param = $this->xml->createElement('param', $offerEntity->price_recurrent);
                    $param->setAttribute('name', 'Ежемесячная цена');
                    $param->setAttribute('unit', 'месяц');
                    $offer->appendChild($param);
                }

                if ($offerEntity->price_old) {
                    $param = $this->xml->createElement('price', $offerEntity->price_old);
                    $offer->appendChild($param);

                    $param = $this->xml->createElement('param', $offerEntity->price);
                    $param->setAttribute('name', 'Цена по скидке');
                } else {
                    $param = $this->xml->createElement('price', $offerEntity->price);
                }

                $offer->appendChild($param);

                if ($offerEntity->duration && $offerEntity->duration_unit) {
                    $param = $this->xml->createElement('param', $offerEntity->duration);
                    $param->setAttribute('name', 'Продолжительность');
                    $param->setAttribute('unit', $offerEntity->duration_unit);
                    $offer->appendChild($param);

                    if ($offerEntity->price_recurrent) {
                        $param = $this->xml->createElement('param', $offerEntity->duration);
                        $param->setAttribute('name', 'Оплата в рассрочку');
                        $param->setAttribute('unit', 'месяц');
                        $offer->appendChild($param);
                    }
                }

                if ($offerEntity->program) {
                    for ($i = 0; $i < count($offerEntity->program); $i++) {
                        $value = $this->removeHtml($offerEntity->program[$i]->description);
                        $param = $this->xml->createElement('param');
                        $param->setAttribute('name', 'План');
                        $param->setAttribute('unit', $offerEntity->program[$i]->unit);
                        $param->setAttribute('order', $i + 1);
                        $param->appendChild($this->xml->createCDATASection($value));
                        $offer->appendChild($param);
                    }
                }

                $offers->appendChild($offer);
                $this->fireEvent('export', [$offer]);
            } catch (DOMException $error) {
                $this->addError($error);
            }
        }

        $this->root->getElementsByTagName('shop')->item(0)->appendChild($offers);
    }

    /**
     * Получение оффера.
     *
     * @return Generator<Offer>
     */
    private function getOffer(): Generator
    {
        $count = $this->getTotal();

        for ($i = 0; $i <= $count; $i++) {
            $result = $this->getQuery()
                ->limit(1)
                ->offset($i)
                ->first()
                ?->toArray();

            if ($result) {
                if (!$result['text'] || !count($result['directions']) || !$result['image_middle_id']) {
                    continue;
                }

                try {
                    $offer = new Offer();
                    $offer->id = $result['id'];
                    $offer->name = $result['name'];
                    $offer->url = Config::get('app.url') . '/courses/show/' . $result['school']['link'] . '/' . $result['link'];
                    $offer->categoryId = $this->getNegotiatedCategory(Direction::from($result['directions'][0]['id']));
                    $offer->price_recurrent = $result['price_recurrent'];
                    $offer->price = $result['price'] ?? 0;
                    $offer->price_old = $result['price_old'];
                    $offer->currencyId = 'RUR';

                    if ($result['duration'] && $result['duration_unit']) {
                        $offer->duration = $this->getDuration($result['duration'], Duration::from($result['duration_unit']));
                        $offer->duration_unit = $this->getNegotiatedDuration(Duration::from($result['duration_unit']));
                    }

                    $offer->picture = $result['image_middle_id']->path;
                    $offer->description = $result['text'];

                    if ($result['program']) {
                        $offer->program = [];

                        for ($z = 0; $z < count($result['program']); $z++) {
                            $offer->program[] = $this->getProgramItem($result['program'][$z]);
                        }
                    }

                    yield $offer;
                } catch (Throwable $error) {
                    $this->addError($error);
                }
            }
        }
    }

    /**
     * Генерация элемента XML файла: currencies.
     *
     * @return void
     */
    private function generateCurrenciesElement(): void
    {
        try {
            $currencyEntities = $this->getCurrencies();
            $currencies = $this->xml->createElement('currencies');

            foreach ($currencyEntities as $currencyEntity) {
                $currency = $this->xml->createElement('currency');
                $currency->setAttribute('id', $currencyEntity->id);
                $currency->setAttribute('rate', $currencyEntity->rate);

                $currencies->appendChild($currency);
            }

            $this->root->getElementsByTagName('shop')->item(0)->appendChild($currencies);
        } catch (DOMException $error) {
            $this->addError($error);
        }
    }

    /**
     * Получаем сущность школы.
     *
     * @return School
     */
    private function getSchool(): School
    {
        $school = new School();
        $school->name = Config::get('app.name');
        $school->company = 'Courselandia.ru';
        $school->url = Config::get('app.url');
        $school->email = 'support@courselandia.ru';
        $school->picture = 'https://api.courselandia.ru/storage/uploaded/images/logo.png';
        $school->description = 'Мы — каталог курсов IT сферы. Ищите и сравнивайте курсы по различным параметрам: цена, продолжительность и другие критерии.';

        return $school;
    }

    /**
     * Получаем валют.
     *
     * @return Currency[] Массив валют.
     */
    private function getCurrencies(): array
    {
        $currency = new Currency();
        $currency->id = 'RUR';
        $currency->rate = 1;

        return [
            $currency,
        ];
    }

    /**
     * Сохранение результата генерации в файл с курсами.
     *
     * @return void
     */
    private function saveInFile(): void
    {
        $this->xml->formatOutput = true;
        $path = Storage::drive('public-root')->path('courses.xml');
        $this->xml->save($path);
    }

    /**
     * Запрос для получения курсов.
     *
     * @return Builder Запрос.
     */
    private function getQuery(): Builder
    {
        return Course::with([
            'school' => function ($query) {
                $query->select([
                    'schools.id',
                    'schools.link',
                ])->where('status', true);
            },
            'directions' => function ($query) {
                $query->where('status', true);
            },
        ])
            ->where('status', Status::ACTIVE->value)
            ->whereHas('school', function ($query) {
                $query->where('status', true);
            });
    }

    /**
     * Получение курса из рубрикатора категорий Яндекса.
     *
     * @parma Direction $direction Направление.
     *
     * @return int ID тегории.
     */
    private function getNegotiatedCategory(Direction $direction): int
    {
        if ($direction === Direction::PROGRAMMING) {
            return 100;
        }

        if ($direction === Direction::MARKETING) {
            return 500;
        }

        if ($direction === Direction::DESIGN) {
            return 400;
        }

        if ($direction === Direction::BUSINESS) {
            return 200;
        }

        if ($direction === Direction::ANALYTICS) {
            return 300;
        }

        if ($direction === Direction::GAMES) {
            return 700;
        }

        return 806;
    }

    /**
     * Получение продолжительности.
     *
     * @param int $value Продолжительность.
     * @param Duration $duration Единица продолжительности.
     *
     * @return int Продолжительность.
     */
    private function getDuration(int $value, Duration $duration): int
    {
        if ($duration === Duration::DAY) {
            return $value;
        }

        if ($duration === Duration::WEEK) {
            return $value * 7;
        }

        if ($duration === Duration::MONTH) {
            return $value;
        }

        if ($duration === Duration::YEAR) {
            return $value * 12;
        }

        return $value;
    }

    /**
     * Получение единицы продолжительности из рубрикатора Яндекса.
     *
     * @param Duration $duration Единица продолжительности.
     *
     * @return string Единица продолжительности.
     */
    private function getNegotiatedDuration(Duration $duration): string
    {
        if ($duration === Duration::DAY) {
            return 'день';
        }

        if ($duration === Duration::WEEK) {
            return 'день';
        }

        return 'месяц';
    }

    /**
     * Получение пункта программы.
     *
     * @param array $item Массив данных пункта программы.
     *
     * @return ProgramItem Сущность пункта программы.
     */
    private function getProgramItem(array $item): ProgramItem
    {
        $programItem = new ProgramItem();
        $programItem->unit = $item['name'];
        $programItem->description = $item['text'];

        if (isset($item['children'])) {
            $description = '';

            for ($i = 0; $i < count($item['children']); $i++) {
                $programItemInside = $this->getProgramItem($item['children'][$i]);
                $description .= trim($programItemInside->unit) . ": \n" . trim($programItemInside->description) . ";\n";
            }

            $programItem->description .= "\n" . $description;
        }

        return $programItem;
    }

    /**
     * Удаление спецсимволов недопустимых в XML тэге.
     *
     * @param string $string Строка для перевода.
     * @return string Обработанная строка.
     */
    private function removeSpecSymbols(string $string): string
    {
        return str_replace(
            ['A', '&', '"', '&',],
            ['A', '&', '\'', '',],
            trim($string)
        );
    }

    /**
     * Удаление HTML тэгов.
     *
     * @param string $string Строка для очистки.
     * @return string Очищенная строка.
     */
    private static function removeHtml(string $string): string
    {
        $string = str_replace('<li>', '', $string);
        $string = str_replace('</li>', ' ', $string);
        $string = strip_tags($string);
        $string = str_replace('', '', $string);

        return html_entity_decode($string);
    }
}
