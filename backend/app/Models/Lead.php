<?php

namespace App\Models;

use App\Enums\LeadPriority;
use App\Enums\LeadStatus;
use App\Enums\LeadTemperature;
use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use BelongsToOrganization, HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'branch_id',
        'student_id',
        'lead_source_id',
        'assigned_counselor_id',
        'lead_status',
        'priority',
        'temperature',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'lead_status' => LeadStatus::class,
            'priority' => LeadPriority::class,
            'temperature' => LeadTemperature::class,
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

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(LeadSource::class, 'lead_source_id');
    }

    public function counselor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_counselor_id');
    }

    public function followups(): HasMany
    {
        return $this->hasMany(Followup::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
