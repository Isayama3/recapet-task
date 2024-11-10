<?php

namespace App\Base\Traits\Sort;

use Carbon\Carbon;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterPermitRequestDateBetween implements Filter
{
    public function __invoke(Builder $query, $value,  string $property)
    {
        $relationships = explode('.', $property);
        $table = $relationships[0];
        $operator = '>=';
        $value = Carbon::parse($value);

        if ($property == $table . '.date_to') {
            $operator = '<=';
        }

        $property = $table . '.date';

        $query->whereDate($property, $operator, $value);
    }
}
