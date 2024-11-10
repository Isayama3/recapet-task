<?php

namespace App\Base\Traits\Sort;

use Illuminate\Support\Facades\DB;
use Spatie\QueryBuilder\Sorts\Sort;
use Illuminate\Database\Eloquent\Builder;

class SortRelationshipsByName implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        $direction = $descending ? 'DESC' : 'ASC';
        $data = explode('.', $property);

        //aliasing used tables in the complex relation
        $alias_table2 = "$data[3] as _$data[3]";
        $alias_table3 = "$data[6] as _$data[6]";

        // used aliased table names
        $original_table = "$data[0]";
        $table2_after_alias = "_$data[3]";
        $table3_after_alias = "_$data[6]";

        //defining keys
        $original_table_key_table2 = "$data[1]";
        $table2_key_table1 = "$data[2]";
        $table2_key_table3 = "$data[4]";
        $table3_key_table2 = "$data[5]";
        $sorting_key = "$data[7]";


        $datatype = DB::table('information_schema.COLUMNS')->select('COLUMN_NAME')->where('TABLE_SCHEMA', 'erp')->where('DATA_TYPE', 'varchar')->where(function ($q) {
            $q->where('COLUMN_NAME', 'like', '%number%')
                ->orWhere('COLUMN_NAME', 'like', '%phone%')
                ->orWhere('COLUMN_NAME', 'like', '%code%')
                ->orWhere('COLUMN_NAME', 'like', '%iban%')
                ->orWhere('COLUMN_NAME', 'like', '%company_capital%')
                ->orWhere('COLUMN_NAME', 'like', '%id%')
                ->orWhere('COLUMN_NAME', 'like', '%amount%')
                ->orWhere('COLUMN_NAME', 'like', '%imei%')
                ->orWhere('COLUMN_NAME', 'like', '%uuid%')
                ->orWhere('COLUMN_NAME', 'like', '%transaction%')
                ->orWhere('COLUMN_NAME', 'like', '%fee%')
                ->orWhere('COLUMN_NAME', 'like', '%percentage%')
                ->orWhere('COLUMN_NAME', 'like', '%value%')
                ->orWhere('COLUMN_NAME', 'like', '%sales%');
        })->get()->toArray();
        $if_can_cast = in_array($sorting_key, $datatype);
        $raw_order_by = $if_can_cast ? "$sorting_key $direction" : "$sorting_key $direction";

        $query
            ->leftJoin($alias_table2, "$table2_after_alias.$table2_key_table1", "$original_table.$original_table_key_table2")
            ->leftJoin($alias_table3, "$table3_after_alias.$table3_key_table2", "$table2_after_alias.$table2_key_table3")
            ->addSelect("$table2_after_alias.*", "$table3_after_alias.*")
            ->orderByRaw($raw_order_by);
    }
}
