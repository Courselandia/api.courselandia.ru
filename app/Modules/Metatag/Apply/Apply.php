<?php
/**
 * Модуль Метатэги.
 * Этот модуль содержит все классы для работы с метатегами.
 *
 * @package App\Modules\Metatag
 */

namespace App\Modules\Metatag\Apply;

use App\Models\Error;
use App\Models\Event;
use App\Modules\Metatag\Apply\Tasks\TaskCourse;

/**
 * Массовое название мэтатегов для всех сущностей
 */
class Apply
{
    use Event;
    use Error;

    /**
     * Задания.
     *
     * @var array<Task>
     */
    private array $tasks = [];

    /**
     * Признак того, что нужно только обновить мэтатеги на основе уже введенных шаблонов.
     *
     * @var bool
     */
    public bool $update = false;

    public function __construct()
    {
        $this
            ->addTask(new TaskCourse());
    }

    /**
     * Запуск процесса формирования метатэгов.
     *
     * @return void
     */
    public function do(): void
    {
        $tasks = $this->getTasks();

        foreach ($tasks as $task) {
            $task->onlyUpdate($this->onlyUpdate());

            $task->apply(function () {
                $this->fireEvent('read');
            });

            if ($task->hasError()) {
                foreach ($task->getErrors() as $error) {
                    $this->addError($error);
                }
            }
        }
    }

    /**
     * Получение общего количества формируемых мэтатегов.
     *
     * @return int Количество мэтатегов для генерации.
     */
    public function count(): int
    {
        $count = 0;
        $tasks = $this->getTasks();

        foreach ($tasks as $task) {
            $count += $task->count();
        }

        return $count;
    }

    /**
     * Добавление задания.
     *
     * @param Task $task Задание.
     * @return $this
     */
    public function addTask(Task $task): self
    {
        $this->tasks[] = $task;

        return $this;
    }

    /**
     * Удаление задания.
     *
     * @return $this
     */
    public function clearTasks(): self
    {
        $this->tasks = [];

        return $this;
    }

    /**
     * Получение всех заданий.
     *
     * @return Task[]
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }

    /**
     * Установит или получит признак того, нужно ли только обновлять мэтатэги.
     *
     * @param ?bool $status Если указать, то изменит параметр.
     *
     * @return bool Признак обновления.
     */
    public function onlyUpdate(?bool $status = null): bool
    {
        if ($status !== null) {
            $this->update = $status;
        }

        return $this->update;
    }

    /*
     * Курсы:
     * Title: Курс {course} от {school:genitive} [price:по цене {price}/бесплатно] — Courselandia
     * Description: Приступите к программе обучения прям сейчас онлайн-курса {course} от {school:genitive} выбрав его в каталоге Courselandia, легкий поиск, возможность сравнивать курсы по разным параметрам.
     * Header: {course} от {school:genitive}
     *
     * Направления:
     * Title: Каталог онлайн-курсов по {direction:dative}[countDirectionCourses:: {countDirectionCourses:курс|nominative} для обучения] — Courselandia
     * Description: В каталоге Courselandia вы можете найти интересные курсы по направлению {direction:nominative} [countDirectionCourses:из {countDirectionCourses:вариант|genitive}]. Здесь полное описание курсов, удобный поиск, рейтинги, обучающие программы.
     * Header: Онлайн курсы по {direction:dative}
     *
     * Профессия:
     * Title: Каталог онлайн-курсов по профессии {profession:nominative} [countProfessionCourses:— {countProfessionCourses:курс|nominative} для обучения] — Courselandia
     * Description: Найдите для себя онлайн-курс по профессии {profession:nominative} из каталога Courselandia [countProfessionCourses:— {countProfessionCourses:курс|nominative} на выбор]. Рейтинг онлайн-школ и курсов, легкий поиск, сравнение цен.
     * Header: Онлайн-курсы по профессии {profession:nominative}
     *
     * Категория:
     * Title: Каталог онлайн-курсов по {category:dative}[countCategoryCourses:: {countCategoryCourses:курс|nominative} для обучения с нуля] — Courselandia
     * Description: Выберите обучающий онлайн-курс в категории {category:nominative} в каталоге Courselandia [countCategoryCourses:— {countCategoryCourses:курс|nominative} для вас]. Рейтинги онлайн-школ, сравнение цен, быстрый поиск, сравнение курсов, обучающие программы.
     * Header: Онлайн курсы по {category:dative}
     *
     * Навыки:
     * Title: Каталог онлайн-курсов по {skill:dative}[countSkillCourses:: {countSkillCourses:курс|nominative} для обучения] — Courselandia
     * Description: Подберите обучающий онлайн-курс для получения навыка {skill:nominative} из каталога Courselandia [countSkillCourses:— {countSkillCourses:курс|nominative} для вас]. Сравнение цен, рейтинг онлайн-школ, сравнение курсов.
     * Header: Онлайн курсы по {skill:dative}
     *
     * Инструменты:
     * Title: Онлайн-курсы по изучению инструмента {tool:nominative}[countToolCourses:: {countToolCourses:курс|nominative} для обучения с нуля] — Courselandia
     * Description: Выбирайте обучающий онлайн-курс по изучению инструмента {tool:nominative} из каталога Courselandia [countToolCourses:— {countToolCourses:курс|nominative} на ваш выбор]. Рейтинг онлайн-школ, сравнение цен, легкий поиск онлайн-курсов.
     * Header: Онлайн-курсы по изучению инструмента {tool:nominative}
     *
     * Школы:
     * Title: {school}:[countSchoolCourses: {countSchoolCourses:онлайн-курс|nominative} — ] цены, сравнения, описание программ и курсов — Courselandia
     * Description: Начни учиться в онлайн-школе {school} [countSchoolCourses: — {countSchoolCourses:профессиональный онлайн-курс|nominative} от ведущих преподавателей], подробное описание курсов в каталоге Courselandia.
     * Header: Онлайн-курсы школы {school}
     *
     * Учитель:
     * Title: Преподаватель {teacher} — отзывы, рейтинг[countTeacherCourses:, список из {countTeacherCourses:курс|genitive}] — Courselandia
     * Description: Все курсы преподавателя {teacher} — полный список обучающих онлайн-курсов в каталоге Courselandia.
     */
}
