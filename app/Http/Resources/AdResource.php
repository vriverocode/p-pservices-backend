<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->ulid,
            'title' => $this->title,
            'description' => $this->description,
            'image_path' => $this->image_path,
            'target_url' => $this->target_url,
            'type' => $this->type,
            'display_order' => $this->display_order,
            'bg_color' => $this->bg_color,
            'text_color' => $this->text_color,
        ];
    }
}
