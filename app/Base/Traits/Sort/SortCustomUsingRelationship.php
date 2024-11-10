<?php

namespace App\Base\Traits\Sort;

use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;

class SortCustomUsingRelationship implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';

        $relationships = explode('.', $property);
        $firstTable = $relationships[0];
        $joinedTable = $relationships[1];
        $foreignKey = $relationships[2];
        $sortingColumn = $relationships[3];
        $firstKey = isset($relationships[4]) ? $relationships[4] : 'id';

        return $query->select($firstTable . ".*")
            ->leftJoin($joinedTable, "$joinedTable.$firstKey", '=', "$firstTable.$foreignKey")
            ->orderBy($joinedTable . "." . $sortingColumn, $direction);
    }
}
