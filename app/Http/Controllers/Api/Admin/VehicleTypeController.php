<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\VehicleType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VehicleTypeController extends Controller
{
    public function index()
    {
        $types = VehicleType::orderBy('name')->get();
        return $this->returnSuccess(200, $types);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:vehicle_types,name',
            'slug' => 'required|string|max:50|unique:vehicle_types,slug',
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $type = VehicleType::create($validated);
        return $this->returnSuccess(201, $type);
    }

    public function show(VehicleType $vehicleType)
    {
        return $this->returnSuccess(200, $vehicleType);
    }

    public function update(Request $request, VehicleType $vehicleType)
    {
        $validated = $request->validate([
            'name' => 'string|max:50|unique:vehicle_types,name,' . $vehicleType->id,
            'slug' => 'string|max:50|unique:vehicle_types,slug,' . $vehicleType->id,
            'icon' => 'nullable|string|max:50',
            'is_active' => 'boolean',
        ]);

        $vehicleType->update($validated);
        return $this->returnSuccess(200, $vehicleType);
    }

    public function destroy(VehicleType $vehicleType)
    {
        $vehicleType->delete();
        return $this->returnSuccess(200, ['message' => 'Tipo eliminado']);
    }
}
