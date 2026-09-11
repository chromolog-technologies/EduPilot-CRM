<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Campaign extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'organization_id',
        'name',
        'code',
        'platform',
        'campaign_type',
        'external_campaign_id',
        'status',
        'start_date',
        'end_date',
        'budget',
        'metadata',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
