<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FulfilmentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\FulfilmentType::create(['name' => 'Fulfilled Supplier', 'difference_amount' => 15]);
        \App\Models\FulfilmentType::create(['name' => 'Non-Fulfilled Supplier', 'difference_amount' => 2]);
    }
}
