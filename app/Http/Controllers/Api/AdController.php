<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdResource;
use App\Models\Ad;

class AdController extends Controller
{
    public function index()
    {
        $ads = Ad::where('is_active', true)
            ->orderBy('display_order')
            ->get();

        return $this->returnSuccess(200, AdResource::collection($ads));
    }
}
