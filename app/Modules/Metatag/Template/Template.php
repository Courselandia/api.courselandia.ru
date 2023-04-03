<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Template;

use App\Modules\Metatag\Template\Tags\TagCategory;
use App\Modules\Metatag\Template\Tags\TagCountDirectionCourses;
use App\Modules\Metatag\Template\Tags\TagCountProfessionCourses;
use App\Modules\Metatag\Template\Tags\TagCourse;
use App\Modules\Metatag\Template\Tags\TagDirection;
use App\Modules\Metatag\Template\Tags\TagPrice;
use App\Modules\Metatag\Template\Tags\TagProfession;
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
            ->addTag(new TagCategory())
            ->addTag(new TagDirection())
            ->addTag(new TagProfession())
            ->addTag(new TagPrice())
            ->addTag(new TagCountDirectionCourses())
            ->addTag(new TagCountProfessionCourses());
    }

    /**
     * Получение строки после шаблонизирования.
     *
     * @param string|null $template Шаблон.
     * @param array<string, string>|null $values Значения для шаблонов.
     *
     * @return ?string Вернет результат конвертации после шаблонизирования.
     * @throws TemplateException
     */
    public function convert(?string $template, ?array $values): ?string
    {
        $template = $this->convertConditions($template, $values);
        $template = $this->convertTags($template, $values);
        $template = str_replace(' .', '.', $template);

        return str_replace(['    ', '   ', '  '], ' ', $template);
    }

    /**
     * Конвертируем тэги.
     *
     * @param string|null $template Шаблон.
     * @param array<string, string>|null $values Значения для шаблонов.
     *
     * @return ?string Вернет результат конвертации после шаблонизирования.
     * @throws TemplateException
     */
    private function convertConditions(?string $template, ?array $values): ?string
    {
        if ($template) {
            preg_match_all("/\[[A-Za-z]*:[{}A-Za-zА-Яа-я0-9,.:;|— ]*(\/[{}A-Za-zА-Яа-я0-9,.:;|— ]*)?\]/u", $template, $matches);
            $conditions = [];

            if (isset($matches[0][0])) {
                for ($i = 0; $i < count($matches[0]); $i++) {
                    $conditionTag = $matches[0][$i];
                    $conditions[$matches[0][$i]] = '';

                    preg_match_all("/\[([A-Za-z]*):([{}A-Za-zА-Яа-я0-9,.:;|— ]*)(\/([{}A-Za-zА-Яа-я0-9,.:;|— ]*))?\]/u", $conditionTag, $conditionMatches);

                    if (isset($conditionMatches[1][0]) && isset($conditionMatches[2][0])) {
                        $condition = $conditionMatches[1][0];
                        $conditionTrue = $conditionMatches[2][0];
                        $conditionFalse = $conditionMatches[4][0] ?? '';

                        $tag = $this->convertTagStringToSettings($condition);
                        $tagObject = $this->getTag($tag['name']);
                        $value = $values[$tag['name']] ?? null;
                        $valueTag = trim($tagObject->convert($value, $tag['configs'], $values));
                        $conditions[$matches[0][$i]] = $valueTag ? $conditionTrue : $conditionFalse;
                    }
                }
            }

            $template = str_replace(array_keys($conditions), array_values($conditions), $template);
        }

        return $template;
    }

    /**
     * Конвертируем тэги.
     *
     * @param string|null $template Шаблон.
     * @param array<string, string>|null $values Значения для шаблонов.
     *
     * @return ?string Вернет результат конвертации после шаблонизирования.
     * @throws TemplateException
     */
    private function convertTags(?string $template, ?array $values): ?string
    {
        if ($template) {
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

        return $template;
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
        preg_match_all("/({[А-Яа-яA-Za-z0-9-_.]*:?([А-Яа-яA-Za-z0-9-_.]*\|?)*})/u", $template, $matches);
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
