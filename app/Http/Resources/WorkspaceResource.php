<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkspaceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->ulid,
            'name' => $this->name,
            'is_active' => $this->is_active,
            'schedules' => WorkspaceScheduleResource::collection($this->whenLoaded('schedules')),
        ];
    }
}
