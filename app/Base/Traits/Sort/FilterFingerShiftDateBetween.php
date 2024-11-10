<?php

namespace App\Base\Traits\Sort;

use Carbon\Carbon;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterFingerShiftDateBetween implements Filter
{
    public function __invoke(Builder $query, $value,  string $property)
    {
        $relationships = explode('.', $property);
        $table = $relationships[0];
        $operator = '>=';
        $value_in = Carbon::parse($value);
        if ($property == $table . '.date_to') {
            $operator = '<=';
            $value_in = Carbon::parse($value)->endOfDay();
        }
        $property = $table . '.date';
        $query->where($property, $operator, $value_in);
    }
}
