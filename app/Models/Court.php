<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Court extends Model
{
    use HasFactory, HasUuids, SoftDeletes;
    protected $fillable = [
        'name',
        'type',
        'district_id',
        'created_by',
        'updated_by',
        'deleted_by', 
    ];

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    public function cases(): HasMany
    {
        return $this->hasMany(CourtCase::class);
    }
}
