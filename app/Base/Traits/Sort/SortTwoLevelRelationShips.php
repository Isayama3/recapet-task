<?php

namespace App\Base\Traits\Sort;

use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;

class SortTwoLevelRelationShips implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';
        $data = explode('.', $property);
        $foreignKeyTwo = $data[1];

        $alias_table1 = "$data[2] as _$data[2]";
        $alias_table2 = "$data[3] as _$data[3]";
        $table_after_alias1 = "_$data[2]";
        $table_after_alias2 = "_$data[3]";

        if (isset($data[5])) {
            $foreignKeyTwo = "$data[5]";
        }

        $query->leftJoin(
            $alias_table1,
            "$table_after_alias1.id",
            "$data[0].$data[1]"
        )
            ->leftJoin($alias_table2, "$table_after_alias2.$foreignKeyTwo", "$table_after_alias1.id")
            ->orderBy("$table_after_alias2.$data[4]", "$direction");
    }
}
