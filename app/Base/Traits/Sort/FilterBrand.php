<?php

namespace App\Base\Traits\Sort;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterBrand implements Filter
{
    public function __invoke(Builder $query, $value,  string $property)
    {
        $relationships = explode('.', $property);
        $relationships = $relationships[1];
        
        $query->whereHas('brands', function ($q) use ($value) {
            $q->where('brands.id', $value);
        })->get();
    }
}
