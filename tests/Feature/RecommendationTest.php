<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Repositories\Eloquent\RecommendationRepository;
use App\Models\Restaurant;

class RecommendationTest extends TestCase
{
    /**
     * Test Recommendation with full data.
     *
     * @return void
     */
    public function testRecommendationSuccessCase(){

        $data = [
            'mealName' => 'Grilled Chicken',
            'latitude' => '79.52180100',
            'longitude' => '78.29482400'
        ];

        $response = $this->json('POST',route('api.recommend.meal'), $data);

        $response->assertStatus(200);

        $this->assertCount( 
            3, 
            $response->decodeResponseJson()['data']
        ); 

        $restaurant = new Restaurant();
        $recommendationRepository = new RecommendationRepository($restaurant);
        $restaurants = $recommendationRepository->find($data)->toArray();
        $this->assertEquals($restaurants, $response->decodeResponseJson()['data']);
    }

    /**
     * Test Recommendation Failed Cases Provider.
     *
     * @return void
     */
    public function conversionSuccessfulProvider()
    {
        return [
            [
                'data' =>
                [
                    'mealName' => '',
                    'latitude' => '79.52180100',
                    'longitude' => '78.29482400'
                ]
            ],
            [
                'data' =>
                [
                    'mealName' => 'no meal',
                    'latitude' => '79.52180100',
                    'longitude' => '78.29482400'
                ]
            ],
            [
                'data' =>
                [
                    'mealName' => 'burger',
                    'latitude' => 'no latitude',
                    'longitude' => '78.29482400'
                ]
            ],
            [
                'data' =>
                [
                    'mealName' => 'burger',
                    'latitude' => '',
                    'longitude' => '78.29482400'
                ]
            ],
            [
                'data' =>
                [
                    'mealName' => 'burger',
                    'latitude' => '79.52180100',
                    'longitude' => 'no Longitude'
                ]
            ],
            [
                'data' =>
                [
                    'mealName' => 'burger',
                    'latitude' => '79.52180100',
                    'longitude' => ''
                ]
            ]

        ];
    }

    /**
     * @dataProvider conversionSuccessfulProvider
     */
    public function testRecommendationValidationFailedCase($data){

        $response = $this->json('POST',route('api.recommend.meal'), $data);

        $response->assertStatus(403);

        $response->assertJsonStructure([
            'errors'
        ]);
    }
}
