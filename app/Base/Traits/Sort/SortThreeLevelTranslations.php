<?php

namespace App\Base\Traits\Sort;

use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;

class SortThreeLevelTranslations implements Sort
{
    public function __construct(private string $firstTable, private string $currentTable, private string $translationTable, private string $currentForeign, private string $intersectForeign, private string $sortedColumn)
    {
    }
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';

        $query->leftJoin($this->firstTable, "$this->currentTable.$this->currentForeign", "$this->firstTable.id")
            ->leftJoin($this->translationTable, "$this->translationTable.$this->intersectForeign", "$this->firstTable.$this->intersectForeign")
            ->distinct("$this->currentTable.id")
            ->orderBy("$this->translationTable.$this->sortedColumn", $direction);
    }
}
