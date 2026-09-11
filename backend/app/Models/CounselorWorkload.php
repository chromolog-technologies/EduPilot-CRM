<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounselorWorkload extends Model
{
    protected $fillable = [
        'organization_id',
        'counselor_id',
        'active_leads',
        'pending_followups',
        'overdue_followups',
        'capacity',
        'last_assigned_at',
        'status',
    ];

    protected $casts = [
        'last_assigned_at' => 'datetime',
    ];

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }
}
