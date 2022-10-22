<?php

namespace App\Modules\Publication\Repositories;

use App\Models\Rep\RepositoryQueryBuilder;

class RepositoryQueryBuilderPublication extends RepositoryQueryBuilder
{
    /**
     * Год.
     *
     * @var int|null
     */
    private ?int $year = null;

    /**
     * Получение года.
     *
     * @return int|null Вернет год.
     */
    public function getYear(): int|null
    {
        return $this->year;
    }

    /**
     * Установка год.
     *
     * @param  int|null  $year  Год.
     *
     * @return $this
     */
    public function setYear(int|null $year): self
    {
        $this->year = $year;

        return $this;
    }
}