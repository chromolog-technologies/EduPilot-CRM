<?php

namespace App\Models;

use App\Enums\DocumentStatus;
use App\Models\Concerns\BelongsToOrganization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentDocument extends Model
{
    use BelongsToOrganization, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'student_id',
        'document_requirement_id',
        'file_name',
        'file_path',
        'status',
        'uploaded_by',
        'uploaded_at',
        'reviewed_by',
        'reviewed_at',
        'remarks',
    ];

    protected function casts(): array
    {
        return [
            'status' => DocumentStatus::class,
            'uploaded_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function requirement(): BelongsTo
    {
        return $this->belongsTo(DocumentRequirement::class, 'document_requirement_id');
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
