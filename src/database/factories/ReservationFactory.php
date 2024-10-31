<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Reservation;
use App\Models\User;
use App\Models\Shop;

class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $randomDate = $this->faker->dateTimeBetween('-30 days', '+30 days')->format('Y-m-d');
        
        return [
            'user_id' => function () {
                return User::inRandomOrder()->first()->id;},
            'shop_id' => function () {
                return Shop::inRandomOrder()->first()->id;},
            'date' => $randomDate,
            'time' => $this->faker->randomElement(['9:00', '9:30',  '10:00', '10:30', '11:00', '11:30', '12:00', '12:30', '13:00', '13:30', '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30', '18:00', '18:30', '19:00', '19:30', '20:00', '20:30', '21:00', '21:30', '22:00', '22:30']),
            'number' => $this->faker->numberBetween(1, 5),
        ];
    }
}
