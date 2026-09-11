<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FollowupInstance extends Model
{
    protected $fillable = [
        'organization_id',
        'sequence_id',
        'student_id',
        'lead_id',
        'current_step_id',
        'status',
        'started_at',
        'next_action_at',
        'last_action_at',
        'stop_reason',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'next_action_at' => 'datetime',
        'last_action_at' => 'datetime',
    ];

    public function sequence(): BelongsTo
    {
        return $this->belongsTo(FollowupSequence::class, 'sequence_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
