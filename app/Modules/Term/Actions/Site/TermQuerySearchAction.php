<?php
/**
 * Модуль Термином.
 * Этот модуль содержит все классы для работы с терминами.
 *
 * @package App\Modules\Term
 */

namespace App\Modules\Term\Actions\Site;

use Util;
use Cache;
use App\Models\Enums\CacheTime;
use App\Models\Action;
use App\Modules\Term\Models\Term;

/**
 * Класс действия для получения строки поиска на основе терминов.
 */
class TermQuerySearchAction extends Action
{
    /**
     * Запрос на поиск.
     *
     * @var string
     */
    private string $query;

    /**
     * Массив сопоставлений английской раскладки к русской на клавиатуре.
     *
     * @var array|string[]
     */
    private array $alphabetic = [
        'q' => 'й',
        'w' => 'ц',
        'e' => 'у',
        'r' => 'к',
        't' => 'е',
        'y' => 'н',
        'u' => 'г',
        'i' => 'ш',
        'o' => 'щ',
        'p' => 'з',
        '[' => 'х',
        ']' => 'ъ',
        'a' => 'ф',
        's' => 'ы',
        'd' => 'в',
        'f' => 'а',
        'g' => 'п',
        'h' => 'р',
        'j' => 'о',
        'k' => 'л',
        'l' => 'д',
        ';' => 'ж',
        '\'' => 'э',
        'z' => 'я',
        'x' => 'ч',
        'c' => 'с',
        'v' => 'м',
        'b' => 'и',
        'n' => 'т',
        'm' => 'ь',
        ',' => 'б',
        '.' => 'ю',
    ];

    /**
     * @param string $query Запрос на поиск.
     */
    public function __construct(string $query)
    {
        $this->query = $query;
    }

    /**
     * Метод запуска логики.
     *
     * @return string Строка для поиска.
     */
    public function run(): string
    {
        $queryRussified = $this->getRussified($this->query);
        $queryTermed = $this->getTermed($this->query . ' ' . $queryRussified);

        $result = $this->query;

        if ($queryRussified) {
            $result .= ' ' . $queryRussified;
        }

        if ($queryTermed) {
            $result .= ' ' . $queryTermed;
        }

        return $result;
    }

    /**
     * Получение русифицированного варианта поиска: ghjuhfvvbcn -> программист.
     *
     * @param string $query Запрос на поиск.
     *
     * @return string Русифицированный вариант.
     */
    private function getRussified(string $query): string
    {
        $length = strlen($query);
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $letter = mb_substr($query, $i, 1, 'utf-8');

            if (isset($this->alphabetic[$letter])) {
                $result .= $this->alphabetic[$letter];
            } else if (isset($this->alphabetic[strtolower($letter)])) {
                $result .= strtoupper($this->alphabetic[strtolower($letter)]);
            }
        }

        return $result;
    }

    /**
     * Получение вариантов поиска по терминам.
     *
     * @param string $query Запрос на поиск.
     * @return string Вернет строку поиска.
     */
    private function getTermed(string $query): string
    {
        $queryVariants = $this->getQueryVariants($query);
        $terms = [];

        foreach ($queryVariants as $variant) {
            $cacheKey = Util::getKey('term', 'site', 'search', $variant);

            $term = Cache::tags(['term'])->remember(
                $cacheKey,
                CacheTime::GENERAL->value,
                function () use ($variant) {
                    $term = Term::where('variant', $variant)
                        ->active()
                        ->first();

                    return $term?->term;
                }
            );

            if ($term) {
                $terms[] = $term;
            }
        }

        $terms = array_unique($terms);

        return implode(' ', $terms);
    }

    /**
     * Возвращает варианты запроса.
     *
     * @param string $query Запрос на поиск.
     * @return array<int, string> Массив вариантов запроса.
     */
    private function getQueryVariants(string $query): array
    {
        $variants = [];
        $queries = explode(' ', $query);

        for ($i = 0; $i < count($queries); $i++) {
            if (trim($queries[$i])) {
                $variant = trim($queries[$i]);
                $variants[] = $variant;

                for ($z = $i + 1; $z < count($queries); $z++) {
                    if (trim($queries[$z])) {
                        $variant = $variant . ' ' . trim($queries[$z]);
                        $variants[] = $variant;

                        for ($y = $z + 1; $y < count($queries); $y++) {
                            if (trim($queries[$y])) {
                                $variants[] = $variant . ' ' . trim($queries[$y]);
                            }
                        }
                    }
                }
            }
        }

        return array_unique($variants);
    }
}
