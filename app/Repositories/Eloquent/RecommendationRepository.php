<?php

namespace App\Repositories\Eloquent;

use Illuminate\Support\Collection;

use App\Models\Restaurant;
use App\Repositories\RecommendationRepositoryInterface;

use \DB;

class RecommendationRepository implements RecommendationRepositoryInterface
{

    private $model;

    /**
    * RecommendationRepository Constructor.
    *
    * @param Restaurant $model
    */
   public function __construct(Restaurant $model)
   {
       $this->$model = $model;
   }

   /**
    * @return Collection
    */
   public function find($data = array()): Collection
   {
        $mealName = $data['mealName'];
        $latitude = $data['latitude'];
        $longitude = $data['longitude'];
        
        $restaurants = Restaurant::select(
                '*',
                DB::raw("
                    ( ( 3959 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) 
                    + sin(radians(?)) * sin(radians(latitude ))) ) * 10 
                    + recommendations_count * 5 + meal_recommendations_count * 3 
                    + successful_orders_count * 5 ) AS result"
                )
            )
            ->groupBy("restaurants.id")
            ->orderBy("result", 'DESC')
            ->offset(0)->limit(3)
            ->setBindings([$latitude, $longitude, $latitude])
            
            ->whereHas('meals', function ($q) use ($mealName){
                return $q->where('name','?')->setBindings([$mealName]);
            })
            
        ->get();
        
        return $restaurants;
   }
}