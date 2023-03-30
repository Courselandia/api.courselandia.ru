<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Template;

use App\Modules\Metatag\Template\Tags\TagCourse;
use App\Modules\Metatag\Template\Tags\TagPrice;
use App\Modules\Metatag\Template\Tags\TagSchool;
use JetBrains\PhpStorm\ArrayShape;

/**
 * Шаблонизирование строки.
 */
class Template
{
    /**
     * Массив допустимых тэгов.
     *
     * @var array<Tag>
     */
    private array $tags = [];

    /**
     * Конструктор.
     */
    public function __construct()
    {
        $this
            ->addTag(new TagSchool())
            ->addTag(new TagCourse())
            ->addTag(new TagPrice());
    }

    /**
     * Получение строки после шаблонизирования.
     *
     * @param string $template Шаблон.
     * @param array<string, string>|null $values Значения для шаблонов.
     *
     * @return string Вернет результат конвертации после шаблонизирования.
     * @throws TemplateException
     */
    public function convert(string $template, ?array $values): string
    {
        $tags = $this->getTagStrings($template);
        $replaces = [];

        foreach ($tags as $tagString) {
            $tag = $this->convertTagStringToSettings($tagString);
            $tagObject = $this->getTag($tag['name']);
            $value = $values[$tag['name']] ?? null;
            $replaces[$tagString] = $tagObject->convert($value, $tag['configs'], $values);
        }

        return str_replace(array_keys($replaces), array_values($replaces), $template);
    }

    /**
     * Вернет массив тэгов в виде строки.
     *
     * @param string $template Шаблон.
     *
     * @return array<string> Массив тэгов в виде строки.
     */
    private function getTagStrings(string $template): array
    {
        preg_match_all("({[A-Za-z0-9-_.]*:?([A-Za-z0-9-_.]*\|?)*})", $template, $matches);
        $matches = $matches[0];
        $tags = [];

        if (count($matches)) {
            foreach ($matches as $tagString) {
                if ($tagString) {
                    $tags[] = $tagString;
                }
            }
        }

        return $tags;
    }

    /**
     * Конвертируем строку тэга в полноценные настройки.
     *
     * @param string $tagString Строка тэга.
     *
     * @return array
     */
    #[ArrayShape([
        'name' => 'string',
        'configs' => 'array',
    ])] private function convertTagStringToSettings(string $tagString): array
    {
        $tag = str_replace(['{', '}'], '', $tagString);
        $tagNameAndConfigs = explode(':', $tag);
        $nameTag = $tagNameAndConfigs[0];

        if (isset($tagNameAndConfigs[1])) {
            $configs = explode('|', $tagNameAndConfigs[1]);
        } else {
            $configs = [];
        }

        return [
            'name' => $nameTag,
            'configs' => $configs,
        ];
    }

    /**
     * Вернет обработчик тэга.
     *
     * @param $name
     *
     * @return Tag
     * @throws TemplateException
     */
    private function getTag(string $name): Tag
    {
        $tags = $this->getTags();

        foreach ($tags as $tag) {
            if ($tag->getName() === $name) {
                return $tag;
            }
        }

        throw new TemplateException('Не удалось найти тэг шаблона: ' . $name);
    }

    /**
     * Добавление тэга.
     *
     * @param Tag $tag Тэг.
     * @return $this
     */
    public function addTag(Tag $tag): self
    {
        $this->tags[] = $tag;

        return $this;
    }

    /**
     * Удаление тэга.
     *
     * @return $this
     */
    public function clearTags(): self
    {
        $this->tags = [];

        return $this;
    }

    /**
     * Получение всех тэгов.
     *
     * @return Tag[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}
