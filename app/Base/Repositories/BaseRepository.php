<?php

namespace App\Base\Repositories;

use App\Base\Traits\Custom\HttpExceptionTrait;
use App\Base\Traits\Custom\ResizableImageTrait;
use App\Base\Traits\Logs\ActivityLogTrait;
use App\Base\Traits\Model\FilterSort;
use App\Base\Traits\Response\ApiResponseTrait;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\QueryBuilder\QueryBuilder;

abstract class BaseRepository
{
    use ActivityLogTrait, ApiResponseTrait, ResizableImageTrait, HttpExceptionTrait;

    protected Model $model;
    protected string $modelName;
    protected Builder|QueryBuilder $query;

    protected array $defaultFilters = [];

    public const LIMIT = 10;
    public const ORDER_BY = 'id';
    public const ORDER_DIR = 'desc';

    /**
     * BaseConcrete constructor.
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->query = $model->query();
        $this->model = $model;
        $this->modelName = class_basename($this->model);
    }

    public function setRelations(array $relations): mixed
    {
        if (!empty($relations)) {
            return $this->query->with(...$relations);
        }

        return $this;
    }

    public function setCustomWhen(array $customWhen): mixed
    {
        if ($customWhen['condition']) {
            return $this->query->when($customWhen['condition'], $customWhen['callback']);
        }

        return $this;
    }

    public function freshQuery(): static
    {
        $this->query = $this->model->query();
        return $this;
    }

    public function all(): mixed
    {
        return $this->query->get();
    }

    public function pluck(string $name): mixed
    {
        return $this->query->pluck($name, 'id');
    }

    public function getSelected(string $name): mixed
    {
        return $this->query->get(['id', $name]);
    }

    public function getManySelected($columns = []): mixed
    {
        return $this->query->get($columns);
    }

    public function listNameWhereCondition($name, $column, $value): mixed
    {
        return $this->query->where($column, $value)->get(['id', $name]);
    }

    public function getMoreThanOneSelected(array $fields): mixed
    {
        return $this->query->get($fields);
    }

    public function initFilters(): void
    {
        if (in_array(FilterSort::class, class_uses_recursive($this->model))) {
            $sort_column = method_exists($this->model, 'customSortColumn') ? $this->model->customSortColumn() : '-created_at';
            $this->query = $this->model->setFilters()->defaultSort($sort_column);
        } else {
            $this->query = $this->model->latest();
        }
    }

    public function getAllDataPaginated(int $limit = self::LIMIT, string $orderBy = self::ORDER_BY, string $orderDir = self::ORDER_DIR): LengthAwarePaginator
    {
        return $this->query->orderBy($orderBy, $orderDir)->paginate(request()->per_page ?? $limit);
    }

    /**
     * @param array $attributes
     *
     * @return mixed
     */
    public function create(array $attributes = []): mixed
    {
        if (!empty($attributes)) {
            $filtered = $this->cleanUpAttributes($attributes);
            $model = $this->query->create($filtered);

            $this->propertyLogActivity(
                $model,
                auth()->user(),
                "$this->modelName Created",
                ['action' => 'Creation', 'module' => $this->modelName]
            );

            return $model;
        }

        return $this->throwHttpExceptionForWebAndApi(__("Attributes can not be empty"));
    }

    public function createMany(array $attributes = []): mixed
    {
        if (!empty($attributes)) {
            $filtered = $this->cleanUpAttributes($attributes);
            return $this->model->insert($filtered);
        }

        return false;
    }

    /**
     * @param Model $model
     * @param array $attributes
     *
     * @return mixed
     */
    public function update($id, array $attributes = []): mixed
    {
        if (!empty($attributes)) {
            $filtered = $this->cleanUpAttributes($attributes);
            $query = $this->freshQuery();
            $model = $query->findOrFail($id);
            tap($model)->update($filtered)->fresh();
            $this->propertyLogActivity(
                $model,
                auth()->user(),
                "$this->modelName Updated",
                ['action' => 'Update', 'module' => $this->modelName]
            );

            return $model;
        }

        return $this->throwHttpExceptionForWebAndApi(__("Attributes can not be empty"), 422);
    }

    /**
     * @param Model $model
     * @param string $relation
     * @param array $attributes
     *
     * @return mixed
     */
    public function attach(Model $model, string $relation, array $attributes = []): mixed
    {
        if (!empty($attributes)) {
            return $model->{$relation}()->attach($attributes);
        }
        return false;
    }

    /**
     * @param Model $model
     * @param string $relation
     * @param array $attributes
     *
     * @return mixed
     */
    public function detach(Model $model, string $relation, array $attributes = []): mixed
    {
        if (!empty($attributes)) {
            return $model->{$relation}()->detach($attributes);
        }
        return false;
    }

    /**
     * @param Model $model
     * @param string $relation
     * @param array $attributes
     *
     * @return mixed
     */
    public function sync(Model $model, string $relation, array $attributes = []): mixed
    {
        if (!empty($attributes)) {
            return $model->{$relation}()->sync($attributes);
        }
        return false;
    }

    /**
     * @param null $key
     * @param array $values
     * @param array $attributes
     *
     * @return int|bool
     */
    public function updateAll($key = null, array $values = [], array $attributes = []): int | bool
    {
        if (!empty($attributes)) {
            $filtered = $this->cleanUpAttributes($attributes);
            if ($key && !empty($values)) {
                return $this->query->whereIn($key, $values)->update($filtered);
            }
            return $this->query->update($filtered);
        }
        return false;
    }

    /**
     * @param array $attributes
     * @param null $id
     *
     * @return bool|mixed
     */
    public function createOrUpdate(array $attributes = [], $id = null): mixed
    {
        if (empty($attributes)) {
            return false;
        }

        $filtered = $this->cleanUpAttributes($attributes);
        if ($id) {
            $model = $this->query->find($id);
            return $this->update($model, $filtered);
        }
        return $this->create($filtered);
    }

    /**
     * @param array $attributes
     * @param array $identifier
     *
     * @return bool|mixed
     */
    public function defaultUpdateOrCreate(array $attributes, array $identifier = []): mixed
    {
        if (empty($attributes)) {
            return false;
        }
        return $this->query->updateOrCreate($attributes, $identifier);
    }

    /**
     * @param Model $model
     * @return bool|mixed|null
     * @throws Exception
     */
    public function remove($id): mixed
    {
        $model = $this->query->findOrFail($id);

        // // Check if has relations
        // foreach ($model->getDefinedRelations() as $relation) {
        //     if ($model->$relation()->count()) {
        //         return response()->json([
        //             'status' => 400,
        //             'error' => __("messages.responses.can_not_delete"),
        //             'message' => __("messages.responses.can_not_delete"),
        //         ], 400);
        //     }
        // }

        $this->propertyLogActivity(
            $model,
            auth()->user(),
            "$this->modelName Removed",
            ['action' => 'Removing', 'module' => $this->modelName]
        );
        if ($model->image) {
            $this->deleteImage($model->image);
        }
        $model->delete();
        return response()->json([
            'status' => 200,
            'message' => __("messages.responses.deleted"),
        ], 200);
    }

    public function canRemove(Model $model): bool
    {
        // Check if model has relations
        foreach ($model->getDefinedRelations() as $relation) {
            if ($model->$relation()->count()) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $relations
     * @return static
     */
    public function has(array $relations = []): static
    {
        foreach ($relations as $relation) {
            $this->query->has($relation);
        }
        return $this;
    }

    public function havingRaw($sql): static
    {
        $this->query->havingRaw($sql);
        return $this;
    }

    /**
     * @param array $relations
     * @return $this
     */
    public function withCount(array $relations = []): static
    {
        foreach ($relations as $relation) {
            $this->query->withCount($relation);
        }
        return $this;
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->query->count();
    }

    /**
     * @param $filters
     * @return int
     */
    public function countWithFilters($filters): int
    {
        $query = $this->freshQuery();
        foreach ($this->model->getFilters() as $filter) {
            if (isset($filters[$filter])) {
                $withFilter = "of" . ucfirst($filter);
                $query = $query->$withFilter($filters[$filter]);
            }
        }
        return $query->count();
    }

    /**
     * @return mixed
     */
    public function first(): mixed
    {
        return $this->query->first();
    }

    /**
     * @return bool
     */
    public function exists(): bool
    {
        return $this->query->exists();
    }

    /**
     * @return bool
     */
    public function doesntExist(): bool
    {
        return $this->query->doesntExist();
    }

    /**
     * @param Model $model
     * @param $column
     * @param $value
     * @return void
     */
    public function increment(Model $model, $column, $value): void
    {
        $model->increment($column, $value);
    }

    /**
     * @param Model $model
     * @param $column
     * @param $value
     * @return void
     */
    public function decrement(Model $model, $column, $value): void
    {
        $model->decrement($column, $value);
    }

    /**
     * @param $column
     * @return mixed
     */
    public function sum($column): mixed
    {
        return $this->aggregate('sum', $column);
    }

    /**
     * @param $function
     * @param $column
     * @return mixed
     */
    public function aggregate($function, $column): mixed
    {
        return $this->query->{$function}($column);
    }

    /**
     * @param int $id
     * @param array $relations
     *
     * @return mixed
     */
    public function find(int $id, array $relations = []): mixed
    {
        $query = $this->query;
        if (!empty($relations)) {
            $query = $query->with($relations);
        }
        return $query->find($id);
    }

    /**
     * @param $column
     * @param $data
     * @return mixed
     */
    public function getByKey($column, $data): mixed
    {
        return $this->query->whereIn($column, (array) $data)->get();
    }

    /**
     * @param int $id
     * @param array $relations
     *
     * @return mixed
     */
    public function findOrFail(int $id, array $relations = []): mixed
    {
        $query = $this->query;
        if (!empty($relations)) {
            $query = $query->with($relations);
        }
        return $query->findOrFail($id);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param bool $fail
     * @return mixed
     */
    public function findBy(string $key, mixed $value, bool $fail = true): mixed
    {
        $model = $this->query->where($key, $value);
        return $fail ? $model->firstOrFail() : $model->first();
    }

    /**
     * @param array $wheres
     * @param array|null $data
     * @return mixed
     */
    public function whereOrCreate(array $wheres, array $data = null): mixed
    {
        return $this->query->firstOrCreate($data ?? $wheres, $wheres);
    }

    /**
     * @param array $fields
     * @param bool $applyOrder
     * @param string $orderBy
     * @param string $orderDir
     * @return mixed
     */
    public function findAll(array $fields = ['*'], bool $applyOrder = true, string $orderBy = self::ORDER_BY, string $orderDir = self::ORDER_DIR): mixed
    {
        $query = $this->query;
        if ($applyOrder) {
            $query = $query->orderBy($orderBy, $orderDir);
        }
        return $query->get($fields);
    }

    protected function cleanUpAttributes($attributes): array
    {
        return collect($attributes)->filter(function ($value, $key) {
            return $this->model->isFillable($key);
        })->toArray();
    }

    public function listWhereTableName($table_name)
    {
        return $this->model->where('table_name', $table_name)->get(['id', 'name']);
    }

    public function setRelationWithSpecificCount($relation, $count): mixed
    {
        return $this->query->with([$relation => function ($query) use ($count) {
            $query->latest()->limit($count);
        }]);
    }
}
