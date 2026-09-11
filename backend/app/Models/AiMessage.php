<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiMessage extends Model
{
    protected $fillable = [
        'organization_id',
        'student_id',
        'lead_id',
        'conversation_id',
        'created_by',
        'purpose',
        'language',
        'tone',
        'input_context',
        'generated_message',
        'model',
        'prompt_version',
        'status',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'input_context' => 'array',
        'approved_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
