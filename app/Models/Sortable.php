<?php
/**
 * Ядро базовых классов.
 * Этот пакет содержит ядро базовых классов для работы с основными компонентами и возможностями системы.
 *
 * @package App.Models
 */

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Exceptions\SortException;

/**
 * Система сортировки с возможностью сортировать даже поля в таблицах отношений.
 *
 * @method static Builder sorted(array $sorts):
 */
trait Sortable
{
    /**
     * Массив данных сортировки.
     *
     * @var array
     */
    private array $sort = [
        'query' => null,
        'data' => null,
    ];

    /**
     * Массив доступных сортировок.
     *
     * @var array
     */
    private array $directions = [
        'asc',
        'desc',
        'random',
    ];

    /**
     * Должно быть вызвано перед осуществлением запроса.
     *
     * @param Builder $query Запрос.
     * @param array $data Данные к запросу.
     *
     * @throws SortException
     */
    public function scopeSorted(Builder $query, array $data): void
    {
        $this->sort['query'] = $query;
        $this->sort['data'] = $data;

        if ($this->isValidSort()) {
            $this->checkSortingDirection();

            foreach ($this->sort['data'] as $field => $direction) {
                if ($direction === 'random') {
                    $this->sort['query']->inRandomOrder();
                } else {
                    $field = str_replace('-', '.', $field);
                    $parts = explode('.', $field);

                    if (count($parts) === 2) {
                        try {
                            $table = $this->{$parts[0]}()->getModel()->getTable();

                            if ($table) {
                                $this->sortByRelation($field, $direction);
                            }
                        } catch (Exception $error) {
                        }
                    } else {
                        $this->sortNormally($field, $direction);
                    }
                }
            }
        }
    }

    /**
     * Определить отвечают ли параметры сортировки всем требованиям.
     *
     * @return bool
     */
    private function isValidSort(): bool
    {
        return is_array($this->sort['data']);
    }

    /**
     * Сортировать записи используя колонки модели.
     *
     * @param string $field Поле для сортировки.
     * @param string $direction Направление сортировки.
     *
     * @return void
     */
    private function sortNormally(string $field, string $direction): void
    {
        $this->sort['query']->orderBy($field, $direction);
    }

    /**
     * Сортировать записи используя колонки из моделей отношений.
     *
     * @param string $field Поле для сортировки.
     * @param string $direction Направление сортировки.
     *
     * @return void
     * @throws SortException
     */
    private function sortByRelation(string $field, string $direction): void
    {
        $field = str_replace('-', '.', $field);
        $parts = explode('.', $field);
        $models = [];

        if (count($parts) > 2) {
            $field = array_pop($parts);
            $relations = $parts;
        } else {
            $field = Arr::last($parts);
            $relations = (array)Arr::first($parts);
        }

        foreach ($relations as $index => $relation) {
            $previousModel = $this;

            if (isset($models[$index - 1])) {
                $previousModel = $models[$index - 1];
            }

            $this->checkRelationToSortBy($previousModel, $relation);

            $relation = $previousModel->{$relation}();
            $models[] = $relation->getModel();

            $modelTable = $previousModel->getTable();
            $relationTable = $relation->getModel()->getTable();

            if (get_class($relation) === BelongsTo::class || get_class($relation) === HasOne::class) {
                /**
                 * @var BelongsTo|HasOne $relation
                 */
                $foreignKey = $relation->getForeignKeyName();

                if (!$this->alreadyJoinedForSorting($relationTable)) {
                    if (get_class($relation) === BelongsTo::class) {
                        $this->sort['query']->leftJoin(
                            $relationTable,
                            $modelTable . '.' . $foreignKey,
                            '=',
                            $relationTable . '.id'
                        );
                    } else {
                        $this->sort['query']->leftJoin(
                            $relationTable,
                            $modelTable . '.id',
                            '=',
                            $relationTable . '.' . $foreignKey
                        );
                    }
                }
            } elseif (get_class($relation) === BelongsToMany::class) {
                /**
                 * @var BelongsToMany $relation
                 */
                if (!$this->alreadyJoinedForSorting($relationTable)) {
                    $this->sort['query']->leftJoin(
                        $relation->getTable(),
                        $relation->getQualifiedParentKeyName(),
                        '=',
                        $relation->getTable() . '.' . $relation->getForeignPivotKeyName()
                    )->leftJoin(
                        $relationTable,
                        $relation->getQualifiedRelatedKeyName(),
                        '=',
                        $relation->getTable() . '.' . $relation->getRelatedPivotKeyName()
                    );
                }
            }
        }

        $alias = implode('_', $relations) . '_' . $field;

        if (isset($relationTable)) {
            $this->sort['query']->addSelect([
                $this->getTable() . '.*',
                $relationTable . '.' . $field . ' AS ' . $alias,
            ]);
        }

        $this->sort['query']->orderBy($alias, $direction);
    }

    /**
     * Проверить что желаемый JOIN уже существует.
     *
     * @param string $table Название таблицы.
     *
     * @return bool
     */
    private function alreadyJoinedForSorting(string $table): bool
    {
        return Str::contains(strtolower($this->sort['query']->toSql()), 'join `' . $table . '`');
    }

    /**
     * Проверим, является ли указанное направление верным.
     *
     * @return void
     * @throws SortException
     */
    private function checkSortingDirection(): void
    {
        foreach ($this->sort['data'] as $direction) {
            if (!in_array(strtolower($direction), array_map('strtolower', $this->directions))) {
                throw new SortException(trans('models.isSort.direction', ['direction' => $direction]));
            }
        }
    }

    /**
     * Определяем является ли отношение верным для желаемой сортировки.
     *
     * @param Model $model Модель.
     * @param string $relation Отношение.
     *
     * @return void
     * @throws SortException
     */
    private function checkRelationToSortBy(Model $model, string $relation): void
    {
        if (
            !($model->{$relation}() instanceof HasOne) &&
            !($model->{$relation}() instanceof BelongsTo) &&
            !($model->{$relation}() instanceof BelongsToMany)
        ) {
            throw new SortException(
                trans('models.isSort.relation', ['relation' => $relation, 'type' => get_class($model->{$relation}())])
            );
        }
    }
}
