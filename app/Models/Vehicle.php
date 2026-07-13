<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vehicle extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUlid;

    protected $fillable = [
        'vehicle_model_id',
        'year',
        'plate',
        'color',
        'alias',
        'is_primary',
        'user_id',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'year' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class, 'vehicle_model_id');
    }

    public function scopePrimary($query)
    {
        return $query->where('is_primary', true);
    }
}
