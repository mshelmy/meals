<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Http\Resources\RestaurantCollection;
use App\Models\Restaurant;
use App\Repositories\RecommendationRepositoryInterface;

use \DB;

class RecommendationController extends Controller
{

    private $recommendationRepository;
  
    public function __construct(RecommendationRepositoryInterface $recommendationRepository)
    {
        $this->recommendationRepository = $recommendationRepository;
    }

    /**
     * Meal Recommender
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function recommend(Request $request)
    {
        // Request Parameters
        $data = $request->all();
        
        // Request Validation
        $validator = Validator::make($data, [
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

        $restaurants = $this->recommendationRepository->find($data);

        // No Meals Found
        if(!$restaurants->count()){
            return response()->json(
                [
                    'errors' => ['No Meal Found.']
                ],
                403
            );
        }

        
        return new RestaurantCollection($restaurants);
    }
}
