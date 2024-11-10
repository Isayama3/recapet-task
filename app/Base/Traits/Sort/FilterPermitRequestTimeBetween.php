<?php

namespace App\Base\Traits\Sort;

use Carbon\Carbon;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterPermitRequestTimeBetween implements Filter
{
    public function __invoke(Builder $query, $value,  string $property)
    {
        $relationships = explode('.', $property);
        $table = $relationships[0];
        $operator = '>=';
        $value = Carbon::parse($value)->format('H:i');
        if ($property == $table . '.to') {
            $operator = '<=';
            $property = $table . '.to';
        }

        $query->where($property, $operator, $value);
    }
}
