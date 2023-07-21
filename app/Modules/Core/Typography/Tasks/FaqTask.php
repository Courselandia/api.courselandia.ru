<?php
/**
 * Модуль ядра системы.
 * Этот модуль содержит все классы для работы с ядром системы.
 *
 * @package App\Modules\Core
 */

namespace App\Modules\Core\Typography\Tasks;

use Typography;
use Illuminate\Database\Eloquent\Builder;
use App\Modules\Faq\Models\Faq;

/**
 * Типографирование вопросов-ответов.
 */
class FaqTask extends Task
{
    /**
     * Количество запускаемых заданий.
     *
     * @return int Количество.
     */
    public function count(): int
    {
        return $this->getQuery()->count();
    }

    /**
     * Запуск типографирования текстов.
     *
     * @return void
     */
    public function run(): void
    {
        $faqs = $this
            ->getQuery()
            ->clone()
            ->get();

        foreach ($faqs as $faq) {
            $faq->question = Typography::process($faq->question, true);
            $faq->answer = Typography::process($faq->answer, true);

            $faq->save();

            $this->fireEvent('finished', [$faq]);
        }
    }

    /**
     * Получить запрос на записи, которые нужно типографировать.
     *
     * @return Builder Построитель запроса.
     */
    private function getQuery(): Builder
    {
        return Faq::orderBy('id', 'ASC');
    }
}
