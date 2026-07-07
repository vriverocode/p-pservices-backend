<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\VehicleModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VehicleModelController extends Controller
{
    public function index(Request $request)
    {
        $query = VehicleModel::with(['make', 'type', 'category']);

        if ($request->filled('make_id')) {
            $query->where('vehicle_make_id', $request->make_id);
        }

        if ($request->filled('type_id')) {
            $query->where('vehicle_type_id', $request->type_id);
        }

        $models = $query->orderBy('name')->get();
        return $this->returnSuccess(200, $models);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'required|string|max:100',
            'vehicle_make_id' => 'required|exists:vehicle_makes,id',
            'vehicle_type_id' => 'required|exists:vehicle_types,id',
            'vehicle_category_id' => 'required|exists:vehicle_categories,id',
            'is_active' => 'boolean',
        ]);

        $model = VehicleModel::create($validated);
        return $this->returnSuccess(201, $model->load(['make', 'type', 'category']));
    }

    public function show(VehicleModel $vehicleModel)
    {
        return $this->returnSuccess(200, $vehicleModel->load(['make', 'type', 'category']));
    }

    public function update(Request $request, VehicleModel $vehicleModel)
    {
        $validated = $request->validate([
            'name' => 'string|max:100',
            'slug' => 'string|max:100',
            'vehicle_make_id' => 'exists:vehicle_makes,id',
            'vehicle_type_id' => 'exists:vehicle_types,id',
            'vehicle_category_id' => 'exists:vehicle_categories,id',
            'is_active' => 'boolean',
        ]);

        $vehicleModel->update($validated);
        return $this->returnSuccess(200, $vehicleModel->load(['make', 'type', 'category']));
    }

    public function destroy(VehicleModel $vehicleModel)
    {
        $vehicleModel->delete();
        return $this->returnSuccess(200, ['message' => 'Modelo eliminado']);
    }
}
