<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Ad extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ulid',
        'title',
        'description',
        'image_path',
        'target_url',
        'is_active',
        'type',
        'display_order',
        'bg_color',
        'text_color',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'type' => 'integer',
        'display_order' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Ad $ad) {
            if (empty($ad->ulid)) {
                $ad->ulid = (string) Str::ulid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'ulid';
    }
}
