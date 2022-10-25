<?php
/**
 * Модуль Категорий.
 * Этот модуль содержит все классы для работы с категориями.
 *
 * @package App\Modules\Category
 */

namespace App\Modules\Category\Repositories;

use App\Models\Entity;
use App\Models\Exceptions\ParameterInvalidException;
use App\Models\Exceptions\RecordNotExistException;
use App\Models\Rep\RepositoryQueryBuilder;
use App\Modules\Category\Entities\Category as CategoryEntity;
use App\Models\Rep\RepositoryEloquent;
use App\Models\Rep\Repository;
use Illuminate\Database\Eloquent\Builder;

/**
 * Класс репозитория компонента на основе Eloquent для категорий.
 *
 * @method CategoryEntity|Entity|null get(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 * @method CategoryEntity[]|Entity[] read(RepositoryQueryBuilder $repositoryQueryBuilder = null, Entity $entity = null)
 */
class Category extends Repository
{
    use RepositoryEloquent;

    /**
     * Получение запроса на выборку.
     *
     * @param RepositoryQueryBuilder $repositoryQueryBuilder Конструктор запроса к репозиторию.
     *
     * @return Builder Запрос.
     * @throws ParameterInvalidException
     */
    protected function query(RepositoryQueryBuilder $repositoryQueryBuilder): Builder
    {
        $query = $this->newInstance()->newQuery();
        $query = $this->queryHelper($query, $repositoryQueryBuilder);
        $search = $repositoryQueryBuilder->getSearch();

        if ($search) {
            $query->where(function ($query) use ($search) {
                /**
                 * @var Builder $query
                 */
                $query->where('categories.id', 'LIKE', '%' . $search . '%')
                    ->orWhere('categories.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('categories.text', 'LIKE', '%' . $search . '%');
            });
        }

        return $query;
    }

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
         * @var $model \App\Modules\Category\Models\Category
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
         * @var $model \App\Modules\Category\Models\Category
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
         * @var $model \App\Modules\Category\Models\Category
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->directions()->sync($directions);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }


    ////


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
         * @var $model \App\Modules\Category\Models\Category
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
         * @var $model \App\Modules\Category\Models\Category
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
         * @var $model \App\Modules\Category\Models\Category
         */
        $model = $this->newInstance()->find($id);

        if ($model) {
            $model->professions()->sync($professions);

            return true;
        }

        throw new RecordNotExistException(trans('modules.repositoryEloquent.record_exist'));
    }
}
