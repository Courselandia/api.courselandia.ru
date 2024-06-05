<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Actions\Admin;

use App\Models\Action;

/**
 * Класс действия для отображения виджетов.
 */
class WidgetRenderAction extends Action
{
    /**
     * Текст.
     *
     * @var string
     */
    private string $text;

    /**
     * @param string $text Текст.
     */
    public function __construct(string $text)
    {
        $this->text = $text;
    }

    /**
     * Метод запуска логики.
     *
     * @return string|null Вернет результаты исполнения.
     */
    public function run(): ?string
    {
        return preg_replace_callback('/<component( [a-z-_0-9]+="[a-z-_0-9]+")*><\/component>/i',
            function (array $matches) {
                return $this->transpile($matches[0]);
            },
            $this->text,
        );
    }

    /**
     * Метод переводит тэг в реальный HTML виджета.
     *
     * @param string $tagComponent Строка тэга.
     * @return string Вернет реальный HTML тэга.
     */
    private function transpile(string $tagComponent): string
    {
        $onlyParams = str_replace(['<component ', '></component>'], '', $tagComponent);
        $onlyParams = explode(' ', $onlyParams);
        print_r($onlyParams);
        exit;

        preg_match(
            '/(\s*[a-z-_0-9]+="[a-z-_0-9]+")/i',
            $tagComponent,
            $matches,
        );
        print_r($matches);
        exit;

        return 'HERE!';
    }
}
