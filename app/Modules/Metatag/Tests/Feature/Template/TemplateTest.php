<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Tests\Feature\Template;

use App\Modules\Course\Enums\Currency;
use App\Modules\Metatag\Template\Template;
use App\Modules\Metatag\Template\TemplateException;
use Tests\TestCase;

/**
 * Тестирование: Класс для шаблонизирования.
 */
class TemplateTest extends TestCase
{
    /**
     * Проверка работы шаблонизатора: первый вариант.
     *
     * @return void
     * @throws TemplateException
     */
    public function testTemplateVer1(): void
    {
        $templateValues = [
            'course' => 'Программирование на Java',
            'school' => 'Нетология',
            'price' => 160000,
            'currency' => Currency::RUB,
        ];

        $template = new Template();
        $tmp = 'Курс {course} от {school:genitive} [price:по цене {price}/бесплатно] — Courselandia';

        $result = $template->convert($tmp, $templateValues);
        $this->assertEquals('Курс Программирование на Java от Нетологии по цене 160 000 руб. — Courselandia', $result);
    }

    /**
     * Проверка работы шаблонизатора: второй вариант.
     *
     * @return void
     * @throws TemplateException
     */
    public function testTemplateVer2(): void
    {
        $templateValues = [
            'direction' => 'Программирование',
            'countDirectionCourses' => 622,
        ];

        $template = new Template();
        $tmp = 'В каталоге Courselandia вы можете найти интересные курсы по направлению {direction:nominative} [countDirectionCourses:из {countDirectionCourses:вариант|genitive}].';

        $result = $template->convert($tmp, $templateValues);
        $this->assertEquals('В каталоге Courselandia вы можете найти интересные курсы по направлению Программирование из 622 вариантов.', $result);
    }
}
