<?php

namespace Database\Factories;

use App\Models\Meal;
use Illuminate\Database\Eloquent\Factories\Factory;

class MealFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Meal::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Italian Beef', 'Grilled Beef', 'Creamy Beef', 'Grilled Chicken', 'Turkey Chicken', 
                'Fried Chicken', 'Beef Burger', 'Turkey Burgers', 'Veggie Burger', 'Mushroom Burger'
            ]),
        ];
    }
}
