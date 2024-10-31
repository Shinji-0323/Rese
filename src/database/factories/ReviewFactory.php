<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Review;
use App\Models\User;
use App\Models\Shop;

class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => function () {
                return User::inRandomOrder()->first()->id;},
            'shop_id' => function () {
                return Shop::inRandomOrder()->first()->id;},
            'star' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->text(50),
        ];
    }
}
