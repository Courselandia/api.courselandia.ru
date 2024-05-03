<?php
/**
 * Модуль Промокодов.
 * Этот модуль содержит все классы для работы с промокодами.
 *
 * @package App\Modules\Promocode
 */

namespace App\Modules\Promocode\Imports;

use Generator;
use App\Models\Error;
use App\Modules\Promocode\Entities\ParserPromocode;
use App\Modules\School\Enums\School;

/**
 * Абстрактный класс парсинга промокодов.
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
     * Абстрактный класс для получения промокода.
     *
     * @return Generator<ParserPromocode> Вернет один считанный промокод.
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
