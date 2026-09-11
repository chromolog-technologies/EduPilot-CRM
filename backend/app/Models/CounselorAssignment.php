<?php

namespace App\Models;

use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounselorAssignment extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'student_id',
        'counselor_id',
        'assigned_by',
        'assigned_at',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'assigned_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counselor_id');
    }

    public function assignedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
