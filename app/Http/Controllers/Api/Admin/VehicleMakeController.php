<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\VehicleMake;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VehicleMakeController extends Controller
{
    public function index()
    {
        $makes = VehicleMake::orderBy('name')->get();
        return $this->returnSuccess(200, $makes);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50|unique:vehicle_makes,name',
            'slug' => 'required|string|max:50|unique:vehicle_makes,slug',
            'is_active' => 'boolean',
        ]);

        $make = VehicleMake::create($validated);
        return $this->returnSuccess(201, $make);
    }

    public function show(VehicleMake $vehicleMake)
    {
        return $this->returnSuccess(200, $vehicleMake);
    }

    public function update(Request $request, VehicleMake $vehicleMake)
    {
        $validated = $request->validate([
            'name' => 'string|max:50|unique:vehicle_makes,name,' . $vehicleMake->id,
            'slug' => 'string|max:50|unique:vehicle_makes,slug,' . $vehicleMake->id,
            'is_active' => 'boolean',
        ]);

        $vehicleMake->update($validated);
        return $this->returnSuccess(200, $vehicleMake);
    }

    public function destroy(VehicleMake $vehicleMake)
    {
        $vehicleMake->delete();
        return $this->returnSuccess(200, ['message' => 'Marca eliminada']);
    }
}
