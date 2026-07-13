<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Service;
use App\Models\ServicePricing;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServicePricingResource;

class ServicePricingController extends Controller
{
    public function index(Service $service)
    {
        $pricing = $service->pricing()->with('vehicleCategory')->get();
        return $this->returnSuccess(200, ServicePricingResource::collection($pricing));
    }

    public function store(Request $request, Service $service)
    {
        $validated = $request->validate([
            'vehicle_category_id' => 'required|exists:vehicle_categories,id',
            'price' => 'nullable|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
        ]);

        $validated['service_id'] = $service->id;

        $pricing = ServicePricing::create($validated);
        $pricing->load('vehicleCategory');

        return $this->returnSuccess(201, new ServicePricingResource($pricing));
    }

    public function update(Request $request, Service $service, ServicePricing $pricing)
    {
        $validated = $request->validate([
            'vehicle_category_id' => 'exists:vehicle_categories,id',
            'price' => 'nullable|numeric|min:0',
            'duration_minutes' => 'integer|min:1',
        ]);

        $pricing->update($validated);
        $pricing->load('vehicleCategory');

        return $this->returnSuccess(200, new ServicePricingResource($pricing));
    }

    public function destroy(Service $service, ServicePricing $pricing)
    {
        $pricing->delete();
        return $this->returnSuccess(200, ['message' => 'Pricing deleted']);
    }
}
