<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Resources\RestaurantCollection;
use App\Models\Restaurant;

use \DB;

class RecommendationController extends Controller
{
    /**
     * Meal Recommender
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function recommend(Request $request)
    {
        // Request Validation
        $validator = Validator::make($request->all(), [
            'mealName' => 'required|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric'
        ]);

        // Validation Errors Response
        if ($validator->fails()) {
            return response()->json(
                [
                    'errors' => $validator->errors()->all()
                ],
                403
            );
        }

        // Request Data
        $mealName = $request->mealName;
        $latitude = $request->latitude;
        $longitude = $request->longitude;

        // Ranking Query
        $restaurantsIds = DB::table('restaurants')
            ->join('meal_restaurant', 'restaurants.id', '=', 'meal_restaurant.restaurant_id')
            ->join('meals', 'meals.id', '=', 'meal_restaurant.meal_id')
            ->select(
                'restaurants.id',
                DB::raw("
                    ( ( 3959 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) 
                    + sin(radians(?)) * sin(radians(latitude ))) ) * 10 
                    + recommendations_count * 5 + meal_recommendations_count * 3 
                    + successful_orders_count * 5 ) AS result"
                )
            )
            ->whereRaw('meals.name = ?')
            ->groupBy("restaurants.id")
            ->orderBy("result", 'DESC')
            ->offset(0)
            ->limit(3)
            ->setBindings([$latitude, $longitude, $latitude, $mealName])
            ->pluck('restaurants.id')
            ->toArray();
        
        // No Meals Found
        if(empty($restaurantsIds)){
            return response()->json(
                [
                    'errors' => ['No Meal Found.']
                ],
                403
            );
        }

        // Get Restaurants Resources And Return Response
        $restaurants = Restaurant::whereIn('id', $restaurantsIds)
            ->orderBy(DB::raw('FIELD(`id`, '.implode(',', $restaurantsIds).')'))
            ->get();
        return new RestaurantCollection($restaurants);
    }
}
