<?php

namespace Database\Factories;

use App\Models\Data;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Data>
 */
class DataFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Data::class;

    public function definition()
    {
        return [
            'temperature' => $this->faker->randomFloat(2, -10, 100), // Nhiệt độ giả lập từ -10 đến 50 độ C
            'humidity' => $this->faker->randomFloat(2, 0, 100), // Độ ẩm giả lập từ 0 đến 100%
            'time' => $this->faker->dateTimeBetween('-1 year', 'now'), // Thời gian ngẫu nhiên trong 1 năm qua
        ];
    }
}
