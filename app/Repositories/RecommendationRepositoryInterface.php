<?php
namespace App\Repositories;

use App\Models\Restaurant;
use Illuminate\Support\Collection;

interface RecommendationRepositoryInterface
{
   public function find($data = array()): Collection;
}