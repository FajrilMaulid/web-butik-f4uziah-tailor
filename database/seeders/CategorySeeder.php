<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Category::create(['name' => 'Gamis', 'slug' => 'gamis']);
        \App\Models\Category::create(['name' => 'Casual', 'slug' => 'casual']);
        \App\Models\Category::create(['name' => 'Formal', 'slug' => 'formal']);
    }
}
