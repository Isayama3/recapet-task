<?php

namespace App\Base\Traits\Sort;

use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;

class SortUsingRelationship implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';
        $relationships = explode('.', $property);
        $first = "_$relationships[0]";
        $first_alias = "$relationships[0] as _$relationships[0]";
        $second = @$relationships[1];
        $foreignKey = @$relationships[2];
        $sortColumn = @$relationships[3];

        return $query->select($second . ".*")
            ->leftJoin('', "$first.id", '=', "$second.$foreignKey")
            ->orderBy($sortColumn, $direction);
    }
}
