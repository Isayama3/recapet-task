<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Seeder;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Type::insert([
            [
                'id' => 1,
                'name' => 'funding',
            ],
            [
                'id' => 2,
                'name' => 'transfer',
            ],
            [
                'id' => 3,
                'name' => 'receiving',
            ]
        ]);
    }
}
