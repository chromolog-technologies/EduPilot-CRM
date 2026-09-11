<?php

namespace App\Models;

use App\Enums\FollowupStatus;
use App\Enums\FollowupType;
use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Followup extends Model
{
    use BelongsToOrganization, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'student_id',
        'lead_id',
        'assigned_to',
        'followup_type',
        'scheduled_at',
        'status',
        'notes',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'followup_type' => FollowupType::class,
            'status' => FollowupStatus::class,
            'scheduled_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
