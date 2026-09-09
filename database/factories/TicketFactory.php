<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'customer_id' => User::factory(),
            'category_id' => Category::factory(),
            'subject' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'status' => 'open',
        ];
    }
}
