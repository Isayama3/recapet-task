<?php

namespace App\Base\Traits\Sort;

use Carbon\Carbon;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterDateBetweenMonth implements Filter
{
    public function __invoke(Builder $query, $value,  string $property)
    {
        $relationships = explode('.', $property);
        $table = $relationships[0];
        $operator = '>=';

        if ($property == $table . '.end_at') {
            $operator = '<=';
            $property = $table . '.end_at';
        } else {
            $property = $table . '.start_at';
        }

        $query->where($property, $operator, $value);
    }
}
