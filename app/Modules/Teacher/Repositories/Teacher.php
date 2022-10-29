<?php
/**
 * Модуль Учителей.
 * Этот модуль содержит все классы для работы с учителями.
 *
 * @package App\Modules\Teacher
 */

namespace App\Modules\Teacher\Repositories;

use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Teacher\Entities\Teacher as TeacherEntity;
use App\Models\Rep\RepositoryEloquent;
use App\Models\Rep\Repository;

/**
 * Класс репозитория компонента на основе Eloquent для учителя.
 *
 * @method TeacherEntity|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method TeacherEntity[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method int count(RepositoryQueryBuilder $repositoryQueryBuilder = null)
 */
class Teacher extends Repository
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
         * @var $model \App\Modules\Teacher\Models\Teacher
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
         * @var $model \App\Modules\Teacher\Models\Teacher
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
         * @var $model \App\Modules\Teacher\Models\Teacher
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->directions()->sync($directions);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }

    /**
     * Добавить школу.
     *
     * @param int $id Id записи.
     * @param int $school Id школы.
     * @param array $data Массив дополнительных данных при добавлении.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function schoolAttach(int $id, int $school, array $data = []): bool
    {
        /**
         * @var $model \App\Modules\Teacher\Models\Teacher
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->schools()->attach($school, $data);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }

    /**
     * Убрать irjke.
     *
     * @param int $id Id записи.
     * @param int|null $school Id irjks.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function schoolDetach(int $id, int $school = null): bool
    {
        /**
         * @var $model \App\Modules\Teacher\Models\Teacher
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->schools()->detach($school);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }

    /**
     * Синхронизация школ.
     *
     * @param int $id Id записи.
     * @param array $schools Массив школ.
     *
     * @return bool Вернет успешность операции.
     * @throws RecordNotExistException
     * @throws ParameterInvalidException
     * @version 1.0
     * @since 1.0
     */
    public function schoolSync(int $id, array $schools): bool
    {
        /**
         * @var $model \App\Modules\Teacher\Models\Teacher
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->schools()->sync($schools);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }
}
