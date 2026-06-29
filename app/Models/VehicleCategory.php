<?php

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleCategory extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasUlid;

    protected $guarded = ['id'];
    protected $fillable = [
        'name',
        'slug',
        'icon',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function pricing(): HasMany
    {
        return $this->hasMany(ServicePricing::class);
    }
}
