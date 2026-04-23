<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::create([
            'cat_name' => 'Gaji',
            'type' => 'income',
        ]);
        Category::create([
            'cat_name' => 'Sumbangan',
            'type' => 'expense',
        ]);
        Category::create([
            'cat_name' => 'Streaming',
            'type' => 'income',
        ]);
    }
}
