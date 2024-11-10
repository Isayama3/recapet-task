<?php

namespace App\Base\Traits\Sort;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

class FilterSearchInAllColumns implements Filter
{
    public function __invoke(Builder $query, $value,  string $property)
    {
        $columns = Schema::getColumnListing($query->getModel()->getTable());

        $query->whereAny(
            $columns,
            'LIKE',
            "%$value%"
        );
    }
}
