<?php

namespace App\Base\Traits\Sort;

use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;

class SortThreeLevel implements Sort
{
    public function __construct(private string $firstTable, private string $currentTable, private string $firstForeign, private string $secondTable, private string $secondForeign, private string $firstTranslationTable, private string $sortedColumn)
    {
    }
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';

        $query->leftJoin($this->firstTable, "$this->currentTable.$this->firstForeign", "$this->firstTable.id")
            ->leftJoin($this->secondTable, "$this->firstTable.$this->secondForeign", "$this->firstTable.id")
            ->leftJoin($this->firstTranslationTable, "$this->firstTranslationTable.$this->secondForeign", "$this->firstTable.id")
            ->distinct("$this->currentTable.id")
            ->orderBy("$this->firstTranslationTable.$this->sortedColumn", $direction);
        // $query->with(["$this->relationshipName" =>  function ($q) use ($property,$direction){ $q->orderByTranslation("$property", $direction);}]);
    }
}
