<?php

namespace App\Base\Models;

use Spatie\QueryBuilder\AllowedSort;
use App\Base\Traits\Sort\FilterBrand;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Base\Traits\Sort\FilterDateBetween;
use App\Base\Traits\Sort\FilterUsingAmount;
use App\Base\Traits\Sort\FilterPriceBetween;
use App\Base\Traits\Sort\SortUsingRelationship;
use App\Base\Traits\Sort\FilterCreatedAtBetween;
use App\Base\Traits\Sort\FilterDateBetweenMonth;
use App\Base\Traits\Sort\FilterShiftDateBetween;
use App\Base\Traits\Sort\FilterUpdatedAtBetween;
use App\Base\Traits\Sort\FilterUsingInitialPrice;
use App\Base\Traits\Sort\SortRelationshipsByName;
use App\Base\Traits\Sort\SortTwoLevelRelationShips;
use App\Base\Traits\Sort\SortUserUsingRelationship;
use App\Base\Traits\Sort\FilterAllowanceDateBetween;
use App\Base\Traits\Sort\FilterMinAndMaxPriceBetween;
use App\Base\Traits\Sort\SortCustomUsingRelationship;
use App\Base\Traits\Sort\FilterFingerShiftDateBetween;
use App\Base\Traits\Sort\FilterPermitRequestDateBetween;
use App\Base\Traits\Sort\FilterPermitRequestTimeBetween;
use App\Base\Traits\Sort\FilterSearchInAllColumns;
use App\Base\Traits\Sort\FilterUsingScheduleDateWithinTheDay;

trait FilterSort
{
    abstract public static function filterColumns();

    abstract public static function sortColumns();

    public static function setFilters()
    {
        return QueryBuilder::for(static::class)
            ->allowedFilters(static::filterColumns())
            ->allowedSorts(static::sortColumns());
    }

    public static function sortUsingRelationship($alias, $column, $table = null)
    {
        return AllowedSort::custom($alias, new SortUsingRelationship(), $column, $table);
    }

    public static function sortUserUsingRelationship($alias, $column)
    {
        return AllowedSort::custom($alias, new SortUserUsingRelationship(), $column);
    }

    public static function SortTwoLevelRelationShips($alias, $column)
    {
        return AllowedSort::custom($alias, new SortTwoLevelRelationShips(), $column);
    }

    public static function SortRelationshipsByName($alias, $column)
    {
        return AllowedSort::custom($alias, new SortRelationshipsByName(), $column);
    }

    public static function createdAtBetween($value)
    {
        return AllowedFilter::custom($value, new FilterCreatedAtBetween);
    }

    public static function priceBetween($value)
    {
        return AllowedFilter::custom($value, new FilterPriceBetween);
    }

    public static function minAndMaxPriceBetween($value)
    {
        return AllowedFilter::custom($value, new FilterMinAndMaxPriceBetween);
    }

    public static function brandID($value)
    {
        return AllowedFilter::custom($value, new FilterBrand);
    }

    public static function dateBetween($value)
    {
        return AllowedFilter::custom($value, new FilterDateBetween());
    }

    public static function fingerShiftDateBetween($value)
    {
        return AllowedFilter::custom($value, new FilterFingerShiftDateBetween());
    }

    public static function dateBetweenMonth($value)
    {
        return AllowedFilter::custom($value, new FilterDateBetweenMonth());
    }

    public static function searchInAllColumns($value)
    {
        return AllowedFilter::custom($value, new FilterSearchInAllColumns());
    }

    public static function allowanceDateBetween($value)
    {
        return AllowedFilter::custom($value, new FilterAllowanceDateBetween());
    }

    public static function permitRequestDateBetween($value)
    {
        return AllowedFilter::custom($value, new FilterPermitRequestDateBetween());
    }

    public static function permitRequestTimeBetween($value)
    {
        return AllowedFilter::custom($value, new FilterPermitRequestTimeBetween());
    }

    public static function shiftDateBetween($value)
    {
        return AllowedFilter::custom($value, new FilterShiftDateBetween());
    }

    public static function employeeShiftDateBetween($value)
    {
        return AllowedFilter::custom($value, new FilterShiftDateBetween());
    }

    public static function updatedAtBetween($value)
    {
        return AllowedFilter::custom($value, new FilterUpdatedAtBetween);
    }

    public static function sortCustomUsingRelationship($alias, $column, $table = null)
    {
        return AllowedSort::custom($alias, new SortCustomUsingRelationship(), $column, $table);
    }

    public static function amountBetween($value)
    {
        return AllowedFilter::custom($value, new FilterUsingAmount);
    }

    public static function initialPriceBetween($value)
    {
        return AllowedFilter::custom($value, new FilterUsingInitialPrice);
    }
}
