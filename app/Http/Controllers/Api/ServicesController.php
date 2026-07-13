<?php

namespace App\Http\Controllers\Api;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;

class ServicesController extends Controller
{
    public function index()
    {
        $services = Service::with(['pricing'])
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereHas('pricing')
                  ->orWhere('requires_quote', true);
            })
            ->orderBy('sort_order', 'asc')
            ->get();

        if ($services->isEmpty()) {
            return $this->returnFail(404, 'No services found');
        }

        return $this->returnSuccess(200, ServiceResource::collection($services));
    }

    public function show(Service $service)
    {
        if (!$service->is_active) {
            return $this->returnFail(404, 'Service not found');
        }

        $service->load(['pricing.vehicleCategory']);
        return $this->returnSuccess(200, new ServiceResource($service));
    }

    public function price(Request $request, Service $service)
    {
        $request->validate([
            'vehicle_category_id' => 'required|exists:vehicle_categories,id',
        ]);

        $pricing = $service->pricing()
            ->where('vehicle_category_id', $request->vehicle_category_id)
            ->first();

        if (!$pricing) {
            return $this->returnFail(404, 'No pricing found for this category');
        }

        return $this->returnSuccess(200, [
            'price' => $pricing->price,
            'duration_minutes' => $pricing->duration_minutes,
        ]);
    }
}
