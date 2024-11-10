<?php

namespace App\Base\Traits\Sort;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterPriceBetween implements Filter
{
    public function __invoke(Builder $query, $value,  string $property)
    {
        $relationships = explode('.', $property);
        $table = $relationships[0];
        $operator = '>=';

        if ($property == $table . '.price_to') {
            $operator = '<=';
            $property = $table . '.price';
        } else {
            $property = $table . '.price';
        }

        $query->where($property, $operator, $value);
    }
}
