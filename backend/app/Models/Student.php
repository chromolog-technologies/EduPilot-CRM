<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\StudentStatus;
use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Student extends Model
{
    use BelongsToOrganization, HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'branch_id',
        'pipeline_stage_id',
        'student_code',
        'first_name',
        'last_name',
        'email',
        'phone',
        'alternate_phone',
        'date_of_birth',
        'gender',
        'nationality',
        'address',
        'city',
        'country',
        'academic_summary',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'gender' => Gender::class,
            'status' => StudentStatus::class,
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function pipelineStage(): BelongsTo
    {
        return $this->belongsTo(PipelineStage::class, 'pipeline_stage_id');
    }

    public function lead(): HasOne
    {
        return $this->hasOne(Lead::class);
    }

    public function parentContacts(): HasMany
    {
        return $this->hasMany(ParentContact::class);
    }

    public function counselorAssignments(): HasMany
    {
        return $this->hasMany(CounselorAssignment::class);
    }

    public function activeCounselorAssignment(): HasOne
    {
        return $this->hasOne(CounselorAssignment::class)->where('is_active', true)->latestOfMany();
    }

    public function followups(): HasMany
    {
        return $this->hasMany(Followup::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(StudentDocument::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function stageHistory(): HasMany
    {
        return $this->hasMany(StudentStageHistory::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->first_name.' '.$this->last_name);
    }
}
