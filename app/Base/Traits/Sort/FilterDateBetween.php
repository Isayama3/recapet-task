<?php

namespace App\Base\Traits\Sort;

use Carbon\Carbon;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterDateBetween implements Filter
{
    public function __invoke(Builder $query, $value,  string $property)
    {
        $relationships = explode('.', $property);
        $table = $relationships[0];
        $operator = '>=';
        $value = Carbon::parse($value);
        if ($property == $table . '.end_at') {
            $operator = '<=';
            $value = Carbon::parse($value)->endOfDay();
        }
        $property = $table . '.start_at';
        $query->where($property, $operator, $value);
    }
}
