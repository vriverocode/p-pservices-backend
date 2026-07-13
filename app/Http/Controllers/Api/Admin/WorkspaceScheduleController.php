<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Workspace;
use App\Models\WorkspaceSchedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\WorkspaceScheduleResource;

class WorkspaceScheduleController extends Controller
{
    public function index(Workspace $workspace)
    {
        $schedules = $workspace->schedules()->orderBy('day_of_week')->orderBy('start_time')->get();
        return $this->returnSuccess(200, WorkspaceScheduleResource::collection($schedules));
    }

    public function store(Request $request, Workspace $workspace)
    {
        $validated = $request->validate([
            'day_of_week' => 'required|integer|min:0|max:6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'is_active' => 'boolean',
        ]);

        $validated['workspace_id'] = $workspace->id;

        $overlap = $workspace->schedules()
            ->where('day_of_week', $validated['day_of_week'])
            ->where('is_active', true)
            ->where(function ($q) use ($validated) {
                $q->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                  ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                  ->orWhere(function ($q) use ($validated) {
                      $q->where('start_time', '<=', $validated['start_time'])
                        ->where('end_time', '>=', $validated['end_time']);
                  });
            })
            ->exists();

        if ($overlap) {
            return $this->returnFail(422, 'El horario se traslapa con otro existente');
        }

        $schedule = WorkspaceSchedule::create($validated);
        return $this->returnSuccess(201, new WorkspaceScheduleResource($schedule));
    }

    public function update(Request $request, Workspace $workspace, WorkspaceSchedule $schedule)
    {
        $validated = $request->validate([
            'day_of_week' => 'integer|min:0|max:6',
            'start_time' => 'date_format:H:i',
            'end_time' => 'date_format:H:i|after:start_time',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['start_time']) && isset($validated['end_time'])) {
            $overlap = $workspace->schedules()
                ->where('id', '!=', $schedule->id)
                ->where('day_of_week', $validated['day_of_week'] ?? $schedule->day_of_week)
                ->where('is_active', true)
                ->where(function ($q) use ($validated) {
                    $q->whereBetween('start_time', [$validated['start_time'], $validated['end_time']])
                      ->orWhereBetween('end_time', [$validated['start_time'], $validated['end_time']])
                      ->orWhere(function ($q) use ($validated) {
                          $q->where('start_time', '<=', $validated['start_time'])
                            ->where('end_time', '>=', $validated['end_time']);
                      });
                })
                ->exists();

            if ($overlap) {
                return $this->returnFail(422, 'El horario se traslapa con otro existente');
            }
        }

        $schedule->update($validated);
        return $this->returnSuccess(200, new WorkspaceScheduleResource($schedule));
    }

    public function destroy(Workspace $workspace, WorkspaceSchedule $schedule)
    {
        $schedule->delete();
        return $this->returnSuccess(200, ['message' => 'Schedule deleted']);
    }
}
