<?php
/**
 * Модуль предупреждений.
 * Этот модуль содержит все классы для работы с предупреждениями.
 *
 * @package App\Modules\Alert
 */

namespace App\Modules\Alert\Actions\Admin;

use Alert;
use App\Models\Action;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Класс действия для получения предупреждений.
 */
class AlertReadAction extends Action
{
    /**
     * Начать выборку.
     *
     * @var int|null
     */
    public ?int $offset = null;

    /**
     * Лимит выборки выборку.
     *
     * @var int|null
     */
    public ?int $limit = null;

    /**
     * Если установить true, то получит только прочитанные.
     *
     * @var bool|null
     */
    public ?bool $status = null;

    /**
     * Метод запуска логики.
     *
     * @return array Вернет результаты исполнения.
     */
    #[ArrayShape(['data' => 'array', 'total' => 'int'])] public function run(): array
    {
        $data = Alert::list($this->offset, $this->limit, $this->status);
        $total = Alert::list(null, null, $this->status);

        return [
            'data' => $data ?: [],
            'total' => count($total)
        ];
    }
}
