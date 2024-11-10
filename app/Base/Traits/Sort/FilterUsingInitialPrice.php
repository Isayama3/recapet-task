<?php

namespace App\Base\Traits\Sort;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterUsingInitialPrice implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $input = explode('.', $property);
        $key = $input[1];
        $table = $input[0];
        if ($key == 'initial_price_to') {
            return $query->where($table . '.initial_price', '<=', $value);
        }

        if ($key == 'initial_price_from') {
            return $query->where($table . '.initial_price', '>=', $value);
        }
    }
}
