<?php
/**
 * Модуль Курсов.
 * Этот модуль содержит все классы для работы с курсами.
 *
 * @package App\Modules\Course
 */

namespace App\Modules\Course\Repositories;

use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Course\Entities\Course as CourseEntity;
use App\Models\Rep\RepositoryEloquent;
use App\Models\Rep\Repository;

/**
 * Класс репозитория курсов на основе Eloquent.
 *
 * @method CourseEntity|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method CourseEntity[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method int count(RepositoryQueryBuilder $repositoryQueryBuilder = null)
 */
class Course extends Repository
{
    use RepositoryEloquent;

    /**
     * Добавить направления.
     *
     * @param int $id Id записи.
     * @param int $direction Id направления.
     * @param array $data Массив дополнительных данных при добавлении.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function directionAttach(int $id, int $direction, array $data = []): bool
    {
        /**
         * @var $model \App\Modules\Course\Models\Course
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->directions()->attach($direction, $data);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }

    /**
     * Убрать направление.
     *
     * @param int $id Id записи.
     * @param int|null $direction Id направления.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function directionDetach(int $id, int $direction = null): bool
    {
        /**
         * @var $model \App\Modules\Course\Models\Course
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->directions()->detach($direction);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }

    /**
     * Синхронизация направлений.
     *
     * @param int $id Id записи.
     * @param array $directions Массив направлений.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function directionSync(int $id, array $directions): bool
    {
        /**
         * @var $model \App\Modules\Course\Models\Course
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->directions()->sync($directions);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }


    ///


    /**
     * Добавить профессию.
     *
     * @param int $id Id записи.
     * @param int $profession Id профессии.
     * @param array $data Массив дополнительных данных при добавлении.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function professionAttach(int $id, int $profession, array $data = []): bool
    {
        /**
         * @var $model \App\Modules\Course\Models\Course
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->professions()->attach($profession, $data);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }

    /**
     * Убрать профессию.
     *
     * @param int $id Id записи.
     * @param int|null $profession Id профессии.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function professionDetach(int $id, int $profession = null): bool
    {
        /**
         * @var $model \App\Modules\Course\Models\Course
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->professions()->detach($profession);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }

    /**
     * Синхронизация профессии.
     *
     * @param int $id Id записи.
     * @param array $professions Массив профессий.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function professionSync(int $id, array $professions): bool
    {
        /**
         * @var $model \App\Modules\Course\Models\Course
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->professions()->sync($professions);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }


    ///


    /**
     * Добавить категорию.
     *
     * @param int $id Id записи.
     * @param int $category Id категории.
     * @param array $data Массив дополнительных данных при добавлении.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function categoryAttach(int $id, int $category, array $data = []): bool
    {
        /**
         * @var $model \App\Modules\Course\Models\Course
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->categories()->attach($category, $data);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }

    /**
     * Убрать категорию.
     *
     * @param int $id Id записи.
     * @param int|null $category Id категории.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function categoryDetach(int $id, int $category = null): bool
    {
        /**
         * @var $model \App\Modules\Course\Models\Course
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->categories()->detach($category);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }

    /**
     * Синхронизация категории.
     *
     * @param int $id Id записи.
     * @param array $categories Массив категории.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function categorySync(int $id, array $categories): bool
    {
        /**
         * @var $model \App\Modules\Course\Models\Course
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->categories()->sync($categories);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }


    ///


    /**
     * Добавить навык.
     *
     * @param int $id Id записи.
     * @param int $skill Id навыка.
     * @param array $data Массив дополнительных данных при добавлении.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function skillAttach(int $id, int $skill, array $data = []): bool
    {
        /**
         * @var $model \App\Modules\Course\Models\Course
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->skills()->attach($skill, $data);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }

    /**
     * Убрать навык.
     *
     * @param int $id Id записи.
     * @param int|null $skill Id навыка.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function skillDetach(int $id, int $skill = null): bool
    {
        /**
         * @var $model \App\Modules\Course\Models\Course
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->skills()->detach($skill);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }

    /**
     * Синхронизация навыков.
     *
     * @param int $id Id записи.
     * @param array $skills Массив навыков.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function skillSync(int $id, array $skills): bool
    {
        /**
         * @var $model \App\Modules\Course\Models\Course
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->skills()->sync($skills);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }


    ///


    /**
     * Добавить учителя.
     *
     * @param int $id Id записи.
     * @param int $teacher Id учителя.
     * @param array $data Массив дополнительных данных при добавлении.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function teacherAttach(int $id, int $teacher, array $data = []): bool
    {
        /**
         * @var $model \App\Modules\Course\Models\Course
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->teachers()->attach($teacher, $data);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }

    /**
     * Убрать учителя.
     *
     * @param int $id Id записи.
     * @param int|null $teacher Id учителя.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function teacherDetach(int $id, int $teacher = null): bool
    {
        /**
         * @var $model \App\Modules\Course\Models\Course
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->teachers()->detach($teacher);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }

    /**
     * Синхронизация учителей.
     *
     * @param int $id Id записи.
     * @param array $teachers Массив учителей.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function teacherSync(int $id, array $teachers): bool
    {
        /**
         * @var $model \App\Modules\Course\Models\Course
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->teachers()->sync($teachers);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }


    ///


    /**
     * Добавить инструмент.
     *
     * @param int $id Id записи.
     * @param int $tool Id инструмента.
     * @param array $data Массив дополнительных данных при добавлении.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function toolAttach(int $id, int $tool, array $data = []): bool
    {
        /**
         * @var $model \App\Modules\Course\Models\Course
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->tools()->attach($tool, $data);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }

    /**
     * Убрать инструмент.
     *
     * @param int $id Id записи.
     * @param int|null $tool Id инструмента.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function toolDetach(int $id, int $tool = null): bool
    {
        /**
         * @var $model \App\Modules\Course\Models\Course
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->tools()->detach($tool);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }

    /**
     * Синхронизация инструментов.
     *
     * @param int $id Id записи.
     * @param array $tools Массив инструментов.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function toolSync(int $id, array $tools): bool
    {
        /**
         * @var $model \App\Modules\Course\Models\Course
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->tools()->sync($tools);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }
}
