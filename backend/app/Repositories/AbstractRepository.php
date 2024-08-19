<?php

namespace App\Repositories;

use App\Exceptions\ApiException;
use App\Repositories\Contracts\RepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Throwable;

/**
 * Class AbstractRepository
 *
 * @method findBy[field_key]($value) find on by field key
 */
abstract class AbstractRepository implements RepositoryInterface
{
    /**
     * @var Model|Builder|\Staudenmeir\LaravelUpsert\Query\Builder
     */
    protected $model;

    /**
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;

    /**
     * AbstractRepository constructor.
     */
    protected function __construct()
    {
        $this->query = $this->model::query();
    }

    /**
     * Get query builder object
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getQuery()
    {
        return $this->query;
    }

    /**
     * Create new entity.
     *
     * @return Model
     */
    public function create($data)
    {
        $item = $this->model->create($this->prepareBeforeCreate($data));

        return $item;
    }

    /**
     * Create new record or ignore when PK exist
     *
     * @return \Illuminate\Support\Collection
     */
    public function createOrIgnore($data)
    {
        $newObjects = [];

        foreach ($data as $row) {
            $modelObj = $this->model->firstOrNew($row);
            if (! $modelObj->id) {
                $newObjects[] = $this->create($row);
            }
        }

        return new Collection($newObjects);
    }

    /**
     * Sync group of records.
     *
     * @param  array  $complexFilter
     *
     * @throws \Throwable
     */
    public function syncByBundle($data, $complexFilter = [])
    {
        DB::transaction(function () use ($complexFilter, $data) {
            $this->findAll([], $complexFilter, false)->delete();
            $this->model->insert($data);
        });
    }

    /**
     * Update entity by model or entity id
     *
     * @return Model
     */
    public function update($idOrModel, $data)
    {
        $item = $this->getItem($idOrModel);

        if (! $item) {
            return $this->create($data);
        }

        $item->update($this->prepareBeforeUpdate($data));

        return $item;
    }

    /**
     * Update or create new entity
     *
     * @return Model
     */
    public function updateOrCreate($attributes, $data)
    {
        $id = $this->model->upsert($data, $attributes);

        return $this->model->where(Arr::only($data, $attributes))->first();
    }

    /**
     * Get all records.
     *
     * @param  array  $with
     *                       Eager load results with relation to another models.
     * @param  array  $complexFilter
     *                                Filter result by relations with another models.
     * @param  bool  $returnResults
     *                               Return results collection if true, query object if false.
     * @param  array  $sort
     *                       Content of array is $sort['field'] and $sort['type'].
     * @return Collection|\Illuminate\Database\Query\Builder
     *
     * @throws Exception
     */
    public function findAll($with = [], $complexFilter = [], $returnResults = true, $sort = [])
    {
        $this->resetQuery();
        $this->getGlobalScopes();
        $this->complexFilter($complexFilter);
        $this->sort($sort);
        $this->with($with);

        return $returnResults ? $this->query->get() : $this->query;
    }

    /**
     * Find many by ids
     *
     * @param  bool  $returnResults
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection|static[]
     */
    public function findMany($ids, array $with = [], $returnResults = true)
    {
        $this->getGlobalScopes();
        $this->query->whereKey($ids)->with($with);

        return $returnResults ? $this->query->get() : $this->query;
    }

    /**
     * Find a record by a specific key
     *
     * @param  array  $with
     * @param  array  $complexFilter
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public function findOneBy($with = [], $complexFilter = [])
    {
        $this->getGlobalScopes();

        return $this->complexFilter($complexFilter)->with($with)->first();
    }

    /**
     * Find a record by a specific key of Fail
     *
     * @param  array  $with
     * @param  array  $complexFilter
     * @return \Illuminate\Database\Eloquent\Model|static
     */
    public function findOneByOrFail($with = [], $complexFilter = [])
    {
        $this->getGlobalScopes();

        return $this->complexFilter($complexFilter)->with($with)->firstOrFail();
    }

    public function findOneById($id)
    {
        return $this->findOneByOrFail(complexFilter: [getModelName($this->model) => ['id' => $id]]);
    }

    /**
     * Check if there are global scopes and apply it
     */
    protected function getGlobalScopes(): void
    {
        if (App::runningInConsole()) {
            return;
        }

        $role = 'guest';

        //if (auth()->check()) {
        // TODO: There is no roles at the moment.
        // TODO: Uncomment this line once have roles.
        //$role = auth()->user()->getRoleNames()->first();
        //}

        $role = ucfirst(Str::camel(str_replace('-', '_', $role)));
        $scopeClass = config('repo.appScopesPath').ucfirst(getModelName($this->model)).'\\'.$role.'Scope';
        if (class_exists($scopeClass)) {
            $this->query->withGlobalScope($scopeClass, resolve($scopeClass));
        }
    }

    /**
     * Delete a record by id
     *
     * @return int
     */
    public function delete($id)
    {
        try {
            $count = $this->model->destroy($id);
            $this->deleteMorphRelation($id);

            return $count;
        } catch (Throwable $exception) {
            throw new ApiException(__('messages.failure.related-record-delete'), 400);
        }
    }

    /**
     * Delete All morph relations.
     */
    protected function deleteMorphRelation($id)
    {
        foreach (config('repo.morph_classes') as $morphClass => $morphKey) {
            $morphClass::where([
                "{$morphKey}_type" => $this->model->getMorphClass(),
                "{$morphKey}_id" => $id,
            ])->delete();
        }
    }

    /**
     * Delete all recodes.
     *
     * @param  array  $data
     *
     * @throws \Exception
     */
    public function deleteAll($data)
    {
        foreach ($data as $row) {
            $item = $this->model->where($row)->first();
            if ($item) {
                $item->delete();
            }
        }
    }

    /**
     * Get item by id or return it if is object.
     *
     * @return Model
     */
    protected function getItem($idOrModel)
    {
        return gettype($idOrModel) == 'object' ? $idOrModel : $this->model->find($idOrModel);
    }

    /**
     * Exclude null fields in filter
     *
     * @return array
     */
    protected function excludeNullableFields($filters)
    {
        $prepared_result = [];

        foreach ($filters as $key => $value) {
            if (filled($value)) {
                $prepared_result[$key] = $value;
            }
        }

        return $prepared_result;
    }

    /**
     * Handle filter with different models
     *
     * @return Model
     */
    protected function complexFilter($filters = [])
    {
        if (! $filters) {
            return $this->query;
        }

        foreach ($filters as $modelName => $filterAttr) {
            $filterAttr = $this->excludeNullableFields($filterAttr);

            if ($modelName == getModelName($this->model)) {
                foreach ($filterAttr as $filter => $value) {
                    $method = $this->fieldFilterMethodName($filter);
                    if (method_exists($this, $method)) {
                        $this->{$method}($value);
                    } else {
                        $filter = $this->tableNameWithField($filter);
                        $_filter = is_array($value) && ! empty($value) ? true : false;

                        if ($_filter) {
                            $this->query->whereIn($filter, $value);
                        } else {
                            $this->query->where($filter, $value);
                        }
                    }
                }
            } else {
                $this->extractFilterGroup($filterAttr, $modelName);
            }
        }

        return $this->query;
    }

    /**
     * Extract the group of filters that not belong to the same model
     */
    private function extractFilterGroup($filterAttr, $modelName)
    {
        $this->query->whereHas($modelName, function ($query) use ($filterAttr) {
            foreach ($filterAttr as $filter => $value) {
                $method = $this->fieldFilterMethodName($filter);
                if (method_exists($this, $method)) {
                    $this->{$method}($query, $value);
                } else {
                    $_filter = is_array($value) && ! empty($value) ? true : false;

                    if ($_filter) {
                        $query->whereIn($filter, $value);
                    } else {
                        $query->where($filter, $value);
                    }
                }
            }
        });
    }

    /**
     * Find all with pagination page.
     *
     * @param  array  $with
     *                       Eager load results with relation to another models.
     * @param  array  $complexFilter
     *                                Filter result by relations with another models.
     * @param  int  $perPage
     *                        Limit results per page.
     * @param  array  $sort
     *                       Content of array is $sort['field'] and $sort['type'].
     * @return mixed
     *
     * @throws Exception
     */
    public function paginate($with = [], $complexFilter = [], $perPage = 15, $sort = [])
    {
        $request = request();

        // page = null and limit = 0
        if (is_null($request->page) && is_numeric($request->limit) && $request->limit == 0) {
            return collect();
        }

        // page = 0 and limit = 0
        if (is_numeric($request->limit) && is_numeric($request->page) && $request->page == 0 && $request->limit == 0) {
            return $this->findAll($with, $complexFilter, true, $sort);
        }

        // page = is an number and limit is a number or 0 or null
        return $this->findAll($with, $complexFilter, false, $sort)->paginate($request->limit ?? $perPage)->appends(['filter' => $complexFilter]);
    }

    /**
     * Prepare data before create
     *
     * @return mixed
     */
    protected function prepareBeforeCreate($data)
    {
        if (! isset($data['created_by']) && array_key_exists('created_by', $this->model->getFillable())) {
            $data['created_by'] = null;
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $data;
    }

    /**
     * Prepare data before create
     *
     * @return mixed
     */
    protected function prepareBeforeUpdate($data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $data;
    }

    /**
     * Apply sort criteria on query.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     *
     * @throws \Exception
     */
    protected function sort(array $sort)
    {
        if (empty($sort)) {
            return $this->defaultSorting();
        }
        if (! $this->validSortItem($sort)) {
            throw new Exception('Invalid sort parameters.');
        }

        if ($this->validSortByRelation($sort)) {
            $this->sortByRelation($sort);
            // If Sort by item.
        } elseif ($this->validSortItem($sort)) {
            $this->query->orderBy($sort['field'], $sort['type']);
            // If we choose to Sort using multi sort criteria.
        } else {
            foreach ($sort as $sortItem) {
                $this->sort($sortItem);
            }
        }

        $this->defaultSorting();

        return $this->query;
    }

    /**
     * Add Sorting criteria.
     *
     * @return $this
     */
    protected function addSorting($field, $direction)
    {
        $this->query->orderBy($field, $direction);

        return $this;
    }

    /**
     * Default Sorting criteria.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function defaultSorting()
    {
        // Validate timestamp is enabled on selected model or to apply the sorting
        // By default sort by created at on any sort criteria
        if (config('repo.global_sort') && $this->query->getModel()->timestamps) {
            $this->addSorting($this->query->getModel()->getTable().'.created_at', 'desc');
        }

        return $this->query;
    }

    /**
     * Validate single sorting criteria
     */
    protected function validSortItem(array $sort): bool
    {
        return array_key_exists('field', $sort) && array_key_exists('type', $sort);
    }

    /**
     * If sort criteria has relations.
     *
     * @return bool
     */
    protected function validSortByRelation(array $sort)
    {
        return array_key_exists('relation', $sort);
    }

    /**
     * First or create model
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function firstOrCreate(array $data)
    {
        return $this->query->firstOrCreate(Arr::only($data, ['id']), $data);
    }

    /**
     * return "`table_name`.`field_name`"
     *
     * @param  string  $field
     * @return string
     */
    protected function tableNameWithField($field)
    {
        return $this->model->getTable().".{$field}";
    }

    /**
     * Get filter method name of field.
     *
     * @param  string  $filter
     * @return string
     */
    protected function fieldFilterMethodName($filter)
    {
        return Str::camel(strtolower($filter)).config('repo.filter_method_name');
    }

    /**
     * Eager load With Relations and Check for with or with count.
     *
     * @param  array  $relations
     * @return $this
     */
    protected function with($relations)
    {
        $relations = is_array($relations) ? $relations : func_get_args();
        foreach ($relations as $relation => $constrain) {
            $method = Str::endsWith(is_numeric($relation) ? $constrain : $relation, '_count') ? 'withCount' : 'with';
            $this->query->{$method}(is_numeric($relation) ? [str_replace('_count', '', $constrain)] : [$relation => $constrain]);
        }

        return $this;
    }

    /**
     * Implement findBy{key}($value)
     *
     * @return \App\Repositories\AbstractRepository|\Illuminate\Database\Eloquent\Model|null|object
     */
    public function __call($name, $arguments)
    {
        if (! method_exists($this, $name) && Str::startsWith($name, 'findBy')) {
            $key = strtolower(str_replace('findBy', '', $name));

            return $this->findOneBy([], [getModelName($this->model) => [$key => Arr::first($arguments)]]);
        }
    }

    /**
     * reset query builder
     *
     * @return void
     */
    protected function resetQuery()
    {
        $this->query = $this->model->newQuery();
    }

    /**
     * Sort by relation.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     *
     * @throws Exception
     */
    protected function sortByRelation(array $sortItem)
    {
        $relation = str::ucfirst(str::singular($sortItem['relation']));
        $method = "sortBy{$relation}";
        if (! method_exists($this, $method)) {
            $class = get_class($this);
            throw new Exception("Call to undefined method {$method} in {$class}.");
        }
        $this->{$method}($sortItem['field'], $sortItem['type']);
    }
}
