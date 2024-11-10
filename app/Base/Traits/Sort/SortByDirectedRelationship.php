<?php

namespace App\Base\Traits\Sort;

use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;


class SortByDirectedRelationship implements Sort
{
    public function __construct(private string $firstTable, private string $firstTranslationTable, private string $foreign)
    {
    }
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';
        $employeeTable = ''; // Employee::getTableName();
        $userTable = ''; //User::getTableName();

        $query->leftJoin($employeeTable, "$employeeTable.user_id", "$userTable.id")
            ->leftJoin($this->firstTable, "$employeeTable.$this->foreign", "$this->firstTable.id")
            ->leftJoin($this->firstTranslationTable, "$this->firstTranslationTable.$this->foreign", "$this->firstTable.id")
            ->orderBy("$this->firstTranslationTable.$property", $direction);
        // $query->with(["$this->relationshipName" =>  function ($q) use ($property,$direction){ $q->orderByTranslation("$property", $direction);}]);
    }
}
