<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\SlotService;
use App\Models\Service;

class AppointmentController extends Controller
{
    public function __construct(
        private readonly SlotService $slotService
    ) {}

    public function slots(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'workspace_id' => 'required|exists:workspaces,ulid',
            'service_id' => 'required|exists:services,ulid',
        ]);

        $service = Service::with('pricing')->findOrFail($validated['service_id']);
        $workspaceId = $validated['workspace_id'];

        $pricing = $service->pricing->first();
        $durationMinutes = $pricing?->duration_minutes ?? 60;

        $slots = $this->slotService->getAvailableSlots(
            $workspaceId,
            $validated['date'],
            $durationMinutes
        );

        return $this->returnSuccess(200, [
            'date' => $validated['date'],
            'workspace_id' => $workspaceId,
            'duration_minutes' => $durationMinutes,
            'slots' => $slots,
        ]);
    }
}
