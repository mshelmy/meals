<?php

namespace Database\Factories;

use App\Models\Restaurant;
use Illuminate\Database\Eloquent\Factories\Factory;

class RestaurantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Restaurant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Italian Restaurant', 'Grill Restaurant', 'Beef Restaurant', 'Chicken Restaurant', 'Turkey Restaurant', 
                'German Restaurant', 'Burger Restaurant', 'Chinese Restaurant', 'Egyptian Restaurant', 'Arab Restaurant'
            ]),
            'longitude' => $this->faker->longitude(-180,180),
            'latitude' => $this->faker->latitude(-90, 90),
            'recommendations_count' => $this->faker->numberBetween(0,100),
            'meal_recommendations_count' => $this->faker->numberBetween(0,100),
            'successful_orders_count' => $this->faker->numberBetween(100,10000),
        ];
    }
}
