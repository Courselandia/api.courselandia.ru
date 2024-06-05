<?php
/**
 * Модуль Виджетов.
 * Этот модуль содержит все классы для работы с виджетами, которые можно использовать в публикациях.
 *
 * @package App\Modules\Widget
 */

namespace App\Modules\Widget\Actions\Admin;

use Widget;
use App\Models\Action;
use App\Modules\Widget\Models\Widget as WidgetModel;

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
     * @return string|null Вернет реальный HTML тэга.
     */
    private function transpile(string $tagComponent): ?string
    {
        $onlyParams = str_replace(['<component ', '></component>', '"'], '', $tagComponent);
        $onlyParams = explode(' ', $onlyParams);
        $data = [];

        foreach ($onlyParams as $onlyParam) {
            $onlyParamData = explode('=', $onlyParam);
            $data[$onlyParamData[0]] = $onlyParamData[1];
        }

        if (isset($data['is'])) {
            $params = $data;
            unset($params['is']);

            return Widget::driver($data['is'])->render($this->getValues($data['is']), $params);
        }

        return '';
    }

    /**
     * Получить значения виджета.
     *
     * @param string $index Индекс виджета.
     * @return array<string, Array<string, int> | string | int> Параметры виджета.
     */
    private function getValues(string $index): array
    {
        $widget = WidgetModel::where('index', $index)
            ->with('values')
            ->first();

        if ($widget?->values) {
            $data = [];

            foreach ($widget->values as $value) {
                $data[$value->name] = $value->value;
            }

            return $data;
        }

        return [];
    }
}
