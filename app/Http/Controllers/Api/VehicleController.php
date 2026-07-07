<?php

namespace App\Http\Controllers\Api;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VehicleController extends Controller
{
    public function index(Request $request)
    {
        $vehicles = Vehicle::where('user_id', $request->user()->id)
            ->with(['model.make', 'model.type', 'model.category'])
            ->orderByDesc('is_primary')
            ->orderBy('created_at', 'desc')
            ->get();

        return $this->returnSuccess(200, $vehicles);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'plate' => 'required|string|max:20|unique:vehicles,plate,NULL,id,user_id,' . $request->user()->id,
            'color' => 'required|string|max:30',
            'alias' => 'nullable|string|max:50',
            'is_primary' => 'boolean',
        ]);

        $validated['user_id'] = $request->user()->id;

        if ($validated['is_primary'] ?? false) {
            Vehicle::where('user_id', $request->user()->id)->update(['is_primary' => false]);
        }

        if (!Vehicle::where('user_id', $request->user()->id)->exists()) {
            $validated['is_primary'] = true;
        }

        $vehicle = Vehicle::create($validated);
        return $this->returnSuccess(201, $vehicle->load(['model.make', 'model.type', 'model.category']));
    }

    public function show(Request $request, Vehicle $vehicle)
    {
        if ($vehicle->user_id !== $request->user()->id) {
            return $this->returnFail(403, 'No tienes permiso para ver este vehículo');
        }

        return $this->returnSuccess(200, $vehicle->load(['model.make', 'model.type', 'model.category']));
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        if ($vehicle->user_id !== $request->user()->id) {
            return $this->returnFail(403, 'No tienes permiso para modificar este vehículo');
        }

        $validated = $request->validate([
            'vehicle_model_id' => 'exists:vehicle_models,id',
            'year' => 'integer|min:1900|max:' . (date('Y') + 1),
            'plate' => 'string|max:20|unique:vehicles,plate,' . $vehicle->id . ',id,user_id,' . $request->user()->id,
            'color' => 'string|max:30',
            'alias' => 'nullable|string|max:50',
            'is_primary' => 'boolean',
        ]);

        if (($validated['is_primary'] ?? false) && !$vehicle->is_primary) {
            Vehicle::where('user_id', $request->user()->id)->update(['is_primary' => false]);
        }

        $vehicle->update($validated);
        return $this->returnSuccess(200, $vehicle->fresh()->load(['model.make', 'model.type', 'model.category']));
    }

    public function destroy(Request $request, Vehicle $vehicle)
    {
        if ($vehicle->user_id !== $request->user()->id) {
            return $this->returnFail(403, 'No tienes permiso para eliminar este vehículo');
        }

        $vehicle->delete();
        return $this->returnSuccess(200, ['message' => 'Vehículo eliminado']);
    }

    public function setPrimary(Request $request, Vehicle $vehicle)
    {
        if ($vehicle->user_id !== $request->user()->id) {
            return $this->returnFail(403, 'No tienes permiso para modificar este vehículo');
        }

        Vehicle::where('user_id', $request->user()->id)->update(['is_primary' => false]);
        $vehicle->update(['is_primary' => true]);

        return $this->returnSuccess(200, $vehicle->fresh());
    }
}
