<?php

namespace App\Base\Traits\Sort;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterMinAndMaxPriceBetween implements Filter
{
    public function __invoke(Builder $query, $value,  string $property)
    {
        $relationships = explode('.', $property);
        $table = $relationships[0];
        $operator = '>=';

        if ($property == $table . '.price_to') {
            $operator = '<=';
            $property = $table . '.max_price';
        } else {
            $property = $table . '.min_price';
        }

        $query->where($property, $operator, $value);
    }
}
