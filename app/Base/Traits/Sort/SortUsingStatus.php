<?php

namespace App\Base\Traits\Sort;

use Illuminate\Support\Facades\App;
use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;

class SortUsingStatus implements Sort
{

    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';
        $locale = App::getLocale();
        $query->orderByRaw("JSON_EXTRACT($property, '$.$locale') $direction");
    }
}
