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
    private ?int $offset;

    /**
     * Лимит выборки выборку.
     *
     * @var int|null
     */
    private ?int $limit;

    /**
     * Если установить true, то получит только прочитанные.
     *
     * @var bool|null
     */
    private ?bool $status;

    /**
     * @param int|null $offset Начать выборку.
     * @param int|null $limit Лимит выборки выборку.
     * @param bool|null $status Если установить true, то получит только прочитанные.
     */
    public function __construct(
        ?int  $offset = null,
        ?int  $limit = null,
        ?bool $status = null,
    )
    {
        $this->offset = $offset;
        $this->limit = $limit;
        $this->status = $status;
    }

    /**
     * Метод запуска логики.
     *
     * @return array Вернет результаты исполнения.
     */
    public function run(): array
    {
        $data = Alert::list($this->offset, $this->limit, $this->status);
        $total = Alert::list(null, null, $this->status);

        return [
            'data' => $data ?: [],
            'total' => count($total)
        ];
    }
}
