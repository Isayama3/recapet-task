<?php

namespace App\Base\Traits\Sort;

use Carbon\Carbon;
use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;


class FilterAllowanceDateBetween implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $relationships = explode('.', $property);
        $table = $relationships[0];

        $operator = '>=';
        $value = Carbon::parse($value)->format('Y-m-d');

        if ($property == $table . ".date_to") {
            $operator = '<=';
        }

        $query->whereRaw("date(REPLACE(CONCAT(JSON_EXTRACT(date, '$[0]'), '-01'), '\"', '')) $operator \"$value\" ");
    }
}
