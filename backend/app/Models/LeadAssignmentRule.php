<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadAssignmentRule extends Model
{
    protected $fillable = [
        'organization_id',
        'branch_id',
        'name',
        'priority',
        'is_active',
        'assignment_type',
        'conditions',
        'counselor_ids',
        'max_active_leads',
        'created_by',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'conditions' => 'array',
        'counselor_ids' => 'array',
    ];

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }
}
