<?php

namespace App\Base\Traits\Sort;

use Carbon\Carbon;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterUpdatedAtBetween implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        /**
         * property  table.column
         * split .  [0] => table [1] => column
         *
         */
        $relationships = explode('.', $property);
        $table = $relationships[0];
        $operator = '>=';
        $value = Carbon::parse($value);
        if ($property == $table . '.updated_to') {
            $operator = '<=';
            $value = Carbon::parse($value)->endOfDay();
        }
        $property = $table . '.updated_at';
        $query->where($property, $operator, $value);
    }
}
