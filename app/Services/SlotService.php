<?php

namespace App\Services;

use App\Models\Workspace;
use Carbon\Carbon;

class SlotService
{
    public function getAvailableSlots(string|int $workspaceId, string $date, int $durationMinutes): array
    {
        $workspace = Workspace::with('schedules')->findOrFail($workspaceId);
        $carbon = Carbon::parse($date);
        $dayOfWeek = $carbon->dayOfWeek;
        $isToday = $carbon->isToday();

        $schedules = $workspace->schedules
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true);

        if ($schedules->isEmpty()) {
            return [];
        }

        $slots = [];
        $now = Carbon::now();

        foreach ($schedules as $schedule) {
            $start = Carbon::parse($date . ' ' . $schedule->start_time);
            $end = Carbon::parse($date . ' ' . $schedule->end_time);

            while ($start->copy()->addMinutes($durationMinutes)->lte($end)) {
                $slotEnd = $start->copy()->addMinutes($durationMinutes);

                if ($isToday && $start->lte($now)) {
                    $start->addMinutes($durationMinutes);
                    continue;
                }

                $slots[] = [
                    'time' => $start->format('H:i'),
                    'available' => true,
                ];

                $start->addMinutes($durationMinutes);
            }
        }

        // TODO: Discount slots occupied by existing appointments (feature 005)
        // When appointments table exists, query and mark slots as unavailable

        return $slots;
    }
}
