<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;

class ServicesController extends Controller
{
    public function index()
    {
        $services = Service::with(['pricing'])
            ->orderBy('sort_order', 'asc')
            ->get();

        return $this->returnSuccess(200, ServiceResource::collection($services));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:services,name',
            'description' => 'nullable|string',
            'requires_quote' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'configurable_options' => 'nullable|json',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if (isset($validated['configurable_options']) && is_string($validated['configurable_options'])) {
            $validated['configurable_options'] = json_decode($validated['configurable_options'], true);
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail_url'] = $request->file('thumbnail')->store('services', 'public');
        }

        $service = Service::create($validated);
        $service->load('pricing');

        return $this->returnSuccess(201, new ServiceResource($service));
    }

    public function show(Service $service)
    {
        $service->load(['pricing.vehicleCategory']);
        return $this->returnSuccess(200, new ServiceResource($service));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'string|max:100|unique:services,name,' . $service->id,
            'description' => 'nullable|string',
            'requires_quote' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'configurable_options' => 'nullable|json',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        if (isset($validated['configurable_options']) && is_string($validated['configurable_options'])) {
            $validated['configurable_options'] = json_decode($validated['configurable_options'], true);
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail_url'] = $request->file('thumbnail')->store('services', 'public');
        }

        $service->update($validated);
        $service->load('pricing');

        return $this->returnSuccess(200, new ServiceResource($service));
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return $this->returnSuccess(200, ['message' => 'Service deleted']);
    }
}
