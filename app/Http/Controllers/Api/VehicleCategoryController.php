<?php

namespace App\Http\Controllers\Api;

use App\Models\VehicleCategory;
use App\Http\Controllers\Controller;
use App\Http\Resources\VehicleCategoryResource;

class VehicleCategoryController extends Controller
{
    public function index()
    {
        $categories = VehicleCategory::where('is_active', true)
            ->orderBy('name')
            ->get();

        return $this->returnSuccess(200, VehicleCategoryResource::collection($categories));
    }
}
