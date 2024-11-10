<?php

namespace App\Base\Traits\Sort;

use Spatie\QueryBuilder\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class FilterUsingAmount implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        if (isset(request()->filter['advance_requests.amount_from']) && isset(request()->filter['advance_requests.amount_to'])) {
            $query->whereBetween('amount', [request()->filter['advance_requests.amount_from'], request()->filter['advance_requests.amount_to']]);
        }

        if ($property == ".amount_to") {
            $query->where('amount', '<=', $value);
        }
        if ($property == ".amount_from") {
            $query->where('amount', '>=', $value);
        }
    }
}
