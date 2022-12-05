<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Imports;

use Generator;
use Throwable;
use XMLReader;

/**
 * Абстрактный класс парсинга курсов в формате YML.
 */
abstract class ParserYml extends Parser
{
    /**
     * Категории источника.
     *
     * @var array|null
     */
    private ?array $categories;

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this->categories = $this->readCategories();
    }

    /**
     * Чтение всех категорий источника.
     *
     * @return array|null Массив источников.
     */
    private function readCategories(): ?array
    {
        try {
            $reader = new XMLReader();
            $reader->open($this->getSource());
            $categories = [];

            while ($reader->read()) {
                if ($reader->name === 'categories') {
                    while (!($reader->name === 'categories' && $reader->nodeType === XMLReader::END_ELEMENT)) {
                        if ($reader->name === 'category') {
                            if ($reader->nodeType === XMLReader::ELEMENT) {
                                $id = $reader->getAttribute('id');
                                $reader->read();
                                $categories[$id] = $reader->value;
                            }
                        }


                        $reader->read();
                    }
                }
            }

            return $categories;
        } catch (Throwable $error) {
            $this->addError(
                $this->getSchool()->getLabel()
                . ' | ' . $error->getMessage() . '.'
            );
        }

        return null;
    }

    /**
     * Получение категорий источника.
     *
     * @return array<int, string> Массив категорий источника, где ключ, это ID категории.
     */
    protected function getCategories(): array
    {
        return $this->categories;
    }

    /**
     * Получение данных оффера.
     *
     * @return Generator<array>.
     */
    protected function getOffers(): Generator
    {
        try {
            $reader = new XMLReader();
            $reader->open($this->getSource());
            $categories = $this->getCategories();
            $directions = $this->getDirections();

            if ($categories) {
                while ($reader->read()) {
                    if ($reader->name === 'offer' && $reader->nodeType === XMLReader::ELEMENT) {
                        $offer = [
                            'attributes' => $this->getOfferAttributes($reader),
                            ...$this->getOffer($reader)
                        ];

                        $categoryName = $categories[$offer['categoryId']];

                        if (isset($directions[$categoryName])) {
                            $offer['direction'] = $directions[$categoryName];

                            yield $offer;
                        } else {
                            $this->addError(
                                $this->getSchool()->getLabel()
                                . ' | ' . $offer['name']
                                . ' | Не найдено направление для категории: "' . $categoryName . '".'
                            );
                        }
                    }
                }
            }
        } catch (Throwable $error) {
            $this->addError(
                $this->getSchool()->getLabel()
                . ' | ' . $error->getMessage() . '.'
            );
        }
    }

    /**
     * Получение аттрибутов оффера.
     *
     * @param XMLReader $reader Чтение XML.
     *
     * @return array Массив аттрибутов оффера.
     */
    private function getOfferAttributes(XMLReader $reader): array
    {
        $attributes = [];

        if ($reader->hasAttributes) {
            $attributeCount = $reader->attributeCount;

            for ($i = 0; $i < $attributeCount; $i++) {
                $reader->moveToAttributeNo($i);
                $attributes[$reader->name] = $reader->value;
            }

            $reader->moveToElement();
        }

        return $attributes;
    }

    /**
     * Получение данных оффера.
     *
     * @param XMLReader $reader Чтение XML.
     *
     * @return array Массив данных оффера.
     */
    private function getOffer(XMLReader $reader): array
    {
        $offer = [
            'params' => [],
        ];

        while ($reader->read()) {
            if ($reader->name === 'offer' && $reader->nodeType === XMLReader::END_ELEMENT) {
                break;
            }

            if ($reader->name === 'param') {
                $name = $reader->getAttribute('name');
                $reader->read();

                if ($name) {
                    $offer['params'][$name] = $reader->value;
                }

                $reader->read();
            } elseif ($reader->nodeType === XMLReader::ELEMENT) {
                $name = $reader->name;
                $attributes = [];

                if ($reader->hasAttributes) {
                    $attributeCount = $reader->attributeCount;

                    for ($i = 0; $i < $attributeCount; $i++) {
                        $reader->moveToAttributeNo($i);
                        $attributes[$reader->name] = $reader->value;
                    }
                }

                $reader->read();

                if (count($attributes)) {
                    $offer[$name] = [
                        'value' => $reader->value,
                        'attributes' => $attributes,
                    ];
                } else {
                    $offer[$name] = $reader->value;
                }
            }
        }

        return $offer;
    }
}
