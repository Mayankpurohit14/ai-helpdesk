<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        foreach (['Billing', 'Technical', 'General', 'Booking Issue', 'Account'] as $name) {
            Category::firstOrCreate(['name' => $name]);
        }
    }
}
