<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Action>
 */
class ActionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'device' => $this->faker->word, // Giả lập tên thiết bị
            'action' => $this->faker->randomElement(['on', 'off']), // Giả lập hành động bật/tắt
            'user_id' => 1, // ID người dùng luôn là 1
            'time' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
