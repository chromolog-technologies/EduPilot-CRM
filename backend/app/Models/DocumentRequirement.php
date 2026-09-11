<?php

namespace App\Models;

use App\Enums\RecordStatus;
use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocumentRequirement extends Model
{
    use BelongsToOrganization;

    protected $fillable = [
        'organization_id',
        'name',
        'description',
        'is_required',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'is_required' => 'boolean',
            'status' => RecordStatus::class,
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function studentDocuments(): HasMany
    {
        return $this->hasMany(StudentDocument::class);
    }
}
