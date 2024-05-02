<?php
/**
 * Модуль Промоакций.
 * Этот модуль содержит все классы для работы с промоакциями.
 *
 * @package App\Modules\Promotion
 */

namespace App\Modules\Promotion\Imports;

use Generator;
use App\Models\Error;
use App\Modules\Promotion\Entities\ParserPromotion;
use App\Modules\School\Enums\School;

/**
 * Абстрактный класс парсинга промоакций.
 */
abstract class Parser
{
    use Error;

    /**
     * Источник.
     *
     * @var string
     */
    private string $source;

    /**
     * Конструктор.
     *
     * @param string $source URL источника.
     */
    public function __construct(string $source)
    {
        $this->source = $source;
    }

    /**
     * Абстрактный класс для получения промоакции.
     *
     * @return Generator<ParserPromotion> Вернет одну считанную промоакцию.
     */
    abstract public function read(): Generator;

    /**
     * Вернет школу.
     *
     * @return School Школа.
     */
    abstract public function getSchool(): School;

    /**
     * Вернет источник.
     *
     * @return string URL источника.
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * Установит источник.
     *
     * @param string $source URL источника.
     *
     * @return $this
     */
    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }
}
