<?php
/**
 * Модуль Трудоустройство.
 * Этот модуль содержит все классы для работы с трудоустройствами.
 *
 * @package App\Modules\Employment
 */

namespace App\Modules\Faq\Data;

/**
 * Данные для обновления FAQ.
 */
class FaqUpdate extends FaqCreate
{
    /**
     * ID FAQ.
     *
     * @var int|string
     */
    public int|string $id;

    /**
     * @param int|string $id ID FAQ.
     * @param int|null $school_id ID школы.
     * @param string|null $question Вопрос.
     * @param bool|null $status Ответ.
     */
    public function __construct(
        int|string $id,
        ?int       $school_id = null,
        ?string    $question = null,
        ?bool      $status = null
    )
    {
        $this->id = $id;

        parent::__construct(
            $school_id,
            $question,
            $status
        );
    }
}
