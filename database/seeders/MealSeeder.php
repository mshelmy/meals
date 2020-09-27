<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Meal;
use App\Models\Restaurant;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $meals = Meal::factory()->times(10)->create();
        $meals->each(function (Meal $r) {
            $r->restaurants()->attach( range(1, 10) );
        });
    }
}
