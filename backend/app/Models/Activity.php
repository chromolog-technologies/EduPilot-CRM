<?php

namespace App\Models;

use App\Enums\ActivityType;
use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activity extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'student_id',
        'lead_id',
        'user_id',
        'type',
        'subject',
        'description',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'type' => ActivityType::class,
            'metadata' => 'array',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
