<?php

namespace App\Base\Traits\Sort;

use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;

class SortThreeLevelRelationships implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';

        $data = explode('.', $property);
        $first_table = $data[0];
        $first_pivot = $data[1];
        $second_table = $data[2];
        $second_pivot = $data[3];
        $third_table = $data[4];
        $third_pivot = $data[5];
        $optional_pivot = $data[6] ?? 'id';

        return $query->join($second_table, "$first_table.$first_pivot", '=', "$second_table.$optional_pivot")
            ->join("$third_table", "$second_table.$second_pivot", '=', "$third_table.id")
            ->orderBy("$third_table.$third_pivot", $direction);
    }
}
