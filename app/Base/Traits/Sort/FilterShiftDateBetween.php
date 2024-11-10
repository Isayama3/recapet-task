<?php

namespace App\Base\Traits\Sort;

use Carbon\Carbon;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterShiftDateBetween implements Filter
{
    public function __invoke(Builder $query, $value,  string $property)
    {
        $relationships = explode('.', $property);
        $table = $relationships[0];
        $operator = '>=';
        $value = Carbon::parse($value);
        $property = $table . '.shift_from';
        if ($property == $table . '.shift_to') {
            $operator = '<=';
            $value = Carbon::parse($value)->endOfDay();
        }
        $query->where($property, $operator, $value);
    }
}
