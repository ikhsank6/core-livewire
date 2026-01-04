<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AboutUs extends Model
{
    use HasUuid;

    protected $table = 'about_us';

    protected $fillable = [
        'company_name',
        'description',
        'address',
        'phone',
        'email',
        'whatsapp',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'linkedin',
        'latitude',
        'longitude',
        'map_embed',
        'logo',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get the first active about us record.
     */
    public static function getActive(): ?self
    {
        return static::active()->first();
    }
}
